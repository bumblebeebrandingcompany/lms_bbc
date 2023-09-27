<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Project;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Utils\Util;

class DocumentController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $user = auth()->user();

            $query = Document::leftJoin('projects', 'documents.project_id', '=', 'projects.id')
                        ->leftJoin('users', 'documents.created_by', '=', 'users.id')
                        ->select(['documents.id as id', 'documents.title as title', 'projects.name as project_name', 'users.name as added_by', 'documents.created_at as created_at'])
                        ->groupBy('documents.id');
            
            if(!empty($request->input('project_id'))) {
                $query->where('documents.project_id', $request->input('project_id'));
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use($user) {
                $viewGate      = $user->is_superadmin;
                $editGate      = $user->is_superadmin;
                $deleteGate    = $user->is_superadmin;
                $docGuestViewGate = $user->is_superadmin;
                $docGuestViewUrl = $this->util->generateGuestDocumentViewUrl($row->id);
                $crudRoutePart = 'documents';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'docGuestViewGate',
                    'docGuestViewUrl',
                    'row'
                ));
            });

            $table->addColumn('project_name', function ($row) {
                return $row->project_name ? $row->project_name : '';
            });

            $table->addColumn('added_by', function ($row) {
                return $row->added_by ? $row->added_by : '';
            });

            $table->addColumn('created_at', '
                {{@format_datetime($created_at)}}
            ');

            $table->rawColumns(['actions', 'project_name', 'added_by', 'created_at', 'placeholder']);

            return $table->make(true);
        }

        $projects  = Project::pluck('name', 'id')
                        ->toArray();

        return view('admin.documents.index')
            ->with(compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects  = Project::pluck('name', 'id')
                        ->toArray();

        return view('admin.documents.create')
            ->with(compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocumentRequest $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $input = $request->except(['_token']);
        $input['created_by'] = auth()->user()->id;

        Document::create($input);

        return redirect()->route('admin.documents.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->load('project', 'createdBy');

        return view('admin.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects  = Project::pluck('name', 'id')
                        ->toArray();

        return view('admin.documents.edit')
            ->with(compact('projects', 'document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $input = $request->except(['_method', '_token']);
        $document->update($input);

        return redirect()->route('admin.documents.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $document->delete();

        return back();
    }

    public function massDestroy()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        Document::whereIn('id', request('ids'))
            ->delete();

        return back();
    }

    public function guestView($id)
    {
        $document = Document::findOrFail($id);
        
        return view('admin.documents.guest_view')
            ->with(compact('document'));
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Document();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
