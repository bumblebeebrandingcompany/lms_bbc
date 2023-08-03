<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProjectRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class ProjectController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    /**
    * All Utils instance.
    *
    */
    protected $util;

    /**
    * Constructor
    *
    */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        abort_if(($user->is_agency || $user->is_channel_partner), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $__global_clients_filter = $this->util->getGlobalClientsFilter();

            $query = Project::with(['created_by', 'client'])->select(sprintf('%s.*', (new Project)->table));

            if (!$user->is_superadmin) {
                $query = $query->where(function ($q) use($user) {
                    $q->where('created_by_id', $user->id)
                        ->orWhere('client_id', $user->client_id);
                });
            }

            if(!empty($__global_clients_filter)) {
                $query->whereIn('projects.client_id', $__global_clients_filter);
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use($user) {
                $viewGate      = $user->is_superadmin || $user->is_client;
                $editGate      = $user->is_superadmin || $user->is_client;
                $deleteGate    = $user->is_superadmin;
                $crudRoutePart = 'projects';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->addColumn('created_by_name', function ($row) {
                return $row->created_by ? $row->created_by->name : '';
            });

            $table->addColumn('client_name', function ($row) {
                return $row->client ? $row->client->name : '';
            });

            $table->editColumn('client.email', function ($row) {
                return $row->client ? (is_string($row->client) ? $row->client : $row->client->email) : '';
            });
            $table->editColumn('location', function ($row) {
                return $row->location ? $row->location : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'created_by', 'client']);

            return $table->make(true);
        }

        $users   = User::get();
        $clients = Client::get();

        return view('admin.projects.index', compact('users', 'clients'));
    }

    public function create()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.projects.create', compact('clients', 'created_bies'));
    }

    public function store(StoreProjectRequest $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project_details = $request->except('_token');
        $project_details['created_by_id'] = auth()->user()->id;

        $project = Project::create($project_details);

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $project->id]);
        }

        return redirect()->route('admin.projects.index');
    }

    public function edit(Project $project)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $created_bies = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $project->load('created_by', 'client');

        return view('admin.projects.edit', compact('clients', 'created_bies', 'project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->update($request->all());

        return redirect()->route('admin.projects.index');
    }

    public function show(Project $project)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->load('created_by', 'client', 'projectLeads', 'projectCampaigns');

        return view('admin.projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $project->delete();

        return back();
    }

    public function massDestroy(MassDestroyProjectRequest $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::find(request('ids'));

        foreach ($projects as $project) {
            $project->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if((auth()->user()->is_agency || auth()->user()->is_channel_partner), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Project();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
