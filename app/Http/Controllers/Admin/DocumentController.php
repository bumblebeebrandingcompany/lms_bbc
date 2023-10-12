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
use App\Models\Lead;
use App\Models\LeadEvents;
use App\Utils\Util;
use Illuminate\Support\Facades\Storage;
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
        $input['files'] = $this->uploadFiles($request);
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
        $files = $this->uploadFiles($request);
        if(!empty($files)) {
            $existing_files = $document->files ?? [];
            $input['files'] = array_merge($existing_files, $files);
        }
        $document->update($input);

        return redirect()->route('admin.documents.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        if(!empty($document->files)) {
            foreach ($document->files as $file) {
                $this->__unlinkFile($file);
            }
        }
        $document->delete();

        return back();
    }

    public function massDestroy()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documents = Document::whereIn('id', request('ids'))
                    ->get();
                    
        foreach ($documents as $document) {
            if(!empty($document->files)) {
                foreach ($document->files as $file) {
                    $this->__unlinkFile($file);
                }
            }
            $document->delete();
        }
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

    public function uploadFiles($request)
    {   
        $files = [];
        $uploadable_files = $request->file('files');
        if(!empty($uploadable_files)) {
            $file_path = config('constants.document_files_path');
            //if path does not exist create it
            if(!file_exists(storage_path('app/public/'.config('constants.document_files_path')))){
                Storage::disk('public')->makeDirectory(config('constants.document_files_path'));
            }
            foreach ($uploadable_files as $file) {
                $file_name = time().'_'.$file->getClientOriginalName();
                $file->storeAs('public/'.$file_path, $file_name);
                $files[] = $file_name;
            }
        }
        return $files;
    }
    
    public function removeFile(Request $request, $id)
    {
        try {

            $file_name = $request->input('file');
            $document = Document::findOrFail($id);
            $this->__unlinkFile($file_name);
            $filteredFiles = array_diff($document->files, [$file_name]);
            $document->files = $filteredFiles ?? [];
            $document->save();

            return response()->json(['success' => true, 'message' => __('messages.success')], 200);
        } catch (\Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            return response()->json(['success' => false, 'message' => __('messages.something_went_wrong')], 404); 
        }
    }

    protected function __unlinkFile($file_name)
    {
        $file_path = storage_path('app/public/'.config('constants.document_files_path'));
        if (!empty($file_name) && file_exists($file_path . "/" . $file_name)) {
            unlink($file_path . "/" . $file_name);
        }
    }

    public function getFilteredDocuments(Request $request)
    {
        if($request->ajax()) {
            $query = new Document();
            if(!empty($request->get('project_id'))) {
                $query = $query->where('project_id', $request->get('project_id'));
            }
            $documents = $query->get();
            $lead = Lead::findOrFail($request->get('lead_id'));
            return view('admin.leads.partials.document_card')
                ->with(compact('documents', 'lead'));
        }
    }

    public function getDocumentLogs(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $activities = LeadEvents::with(['lead'])
                        ->where('event_type', 'document_sent')
                        ->select(['webhook_data', 'lead_id', 'sell_do_lead_id', 'created_at'])
                        ->orderBy('created_at', 'desc')
                        ->cursorPaginate(30);
                        
        return view('admin.documents.log')
            ->with(compact('activities'));
    }
}
