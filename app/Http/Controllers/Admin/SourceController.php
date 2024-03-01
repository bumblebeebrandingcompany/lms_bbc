<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroySourceRequest;
use App\Http\Requests\StoreSourceRequest;
use App\Http\Requests\UpdateSourceRequest;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\Source;
use Gate;
use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class SourceController extends Controller
{
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
        if (!auth()->user()->is_superadmin && !auth()->user()->is_client) {
            abort(403, 'Unauthorized.');
        }

        if ($request->ajax()) {
            $query = Source::with(['project', 'campaign'])->select(sprintf('%s.*', (new Source)->table));
            $__global_clients_filter = $this->util->getGlobalClientsFilter();
            if (!empty($__global_clients_filter)) {
                $project_ids = $this->util->getClientsProjects($__global_clients_filter);
                $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
                $query->where(function ($q) use ($project_ids, $campaign_ids) {
                    $q->whereIn('sources.project_id', $project_ids)
                        ->orWhereIn('sources.campaign_id', $campaign_ids);
                })->groupBy('sources.id');
            }
            // $essential_fields=DB::table('leads')->get()->toArray();
            // echo "<pre>";
            // print_r($essential_fields);
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'source_show';
                $user = auth()->user();
                $editGate = 'source_edit' && $user->is_superadmin;
                $deleteGate = 'source_delete' && $user->is_superadmin;
                $webhookSecretGate = $user->is_superadmin;
                $crudRoutePart = 'sources';


                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'webhookSecretGate',
                    'crudRoutePart',
                    'row'
                )
                );
            });

            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->addColumn('campaign_name', function ($row) {
                return $row->campaign ? $row->campaign->name : '';
            });

            $table->editColumn('name', function ($row) {
                $html = $row->name ? $row->name : '';

                return $html;
            });

            $table->rawColumns(['actions', 'placeholder', 'project', 'campaign', 'name']);

            return $table->make(true);
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects = Project::whereIn('id', $project_ids)
            ->get();

        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->get();

        return view('admin.sources.index', compact('projects', 'campaigns'));
    }

    public function create()
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects = Project::whereIn('id', $project_ids)
            ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.sources.create', compact('campaigns', 'projects'));
    }

    public function store(StoreSourceRequest $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }
        $source_details = $request->except('_token');
        $source_details['webhook_secret'] = $this->util->generateWebhookSecret();
        $source = Source::create($source_details);

        return redirect()->route('admin.sources.index');
    }

    public function edit(Source $source)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects = Project::whereIn('id', $project_ids)
            ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $source->load('project', 'campaign');

        return view('admin.sources.edit', compact('campaigns', 'projects', 'source'));
    }

    public function update(UpdateSourceRequest $request, Source $source)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $source_details = $request->except(['_method', '_token']);
        $source->update($source_details);

        return redirect()->route('admin.sources.index');
    }

    public function show(Source $source)
    {
        if (!auth()->user()->is_superadmin && !auth()->user()->is_client) {
            abort(403, 'Unauthorized.');
        }

        $source->load('project', 'campaign');

        return view('admin.sources.show', compact('source'));
    }

    public function destroy(Source $source)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $source->delete();

        return back();
    }

    public function massDestroy(MassDestroySourceRequest $request)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $sources = Source::find(request('ids'));

        foreach ($sources as $source) {
            $source->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function getSource(Request $request)
    {
        if ($request->ajax()) {

            $query = Source::where('project_id', $request->input('project_id'))
                ->where('campaign_id', $request->input('campaign_id'));

            if (auth()->user()->is_channel_partner) {
                $assigned_sources = auth()->user()->sources;
                $query->whereIn('id', $assigned_sources);
            }

            $sources = $query->pluck('name', 'id')
                ->toArray();

            $sources_arr = [['id' => '', 'text' => __('messages.please_select')]];
            if (!empty($sources)) {
                foreach ($sources as $id => $text) {
                    $sources_arr[] = [
                        'id' => $id,
                        'text' => $text
                    ];
                }
            }
            return $sources_arr;
        }
    }
    public function getWebhookDetails($id)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $source = Source::with(['project'])->findOrFail($id);

        $lead = Lead::where('source_id', $id)->latest()->first();
        // $lead=Lead::table('leads')->get()->toArray();
        // echo "<pre>";
        // print_r($lead);

        return view('admin.sources.webhook', compact('source', 'lead'));
    }


    public function updatePhoneAndEmailKey(Request $request)
    {
        $source = Source::findOrFail($request->input('source_id'));
        $source->email_key = $request->input('email_key');
        $source->phone_key = $request->input('phone_key');
        $source->name_key = $request->input('name_key');
        $source->additional_email_key = $request->input('additional_email_key');
        $source->secondary_phone_key = $request->input('secondary_phone_key');
        $source->save();

        return redirect()->route('admin.sources.webhook', $source->id);
    }
}
