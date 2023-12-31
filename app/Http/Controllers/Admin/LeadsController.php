<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\Project;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use App\Exports\LeadsExport;
use App\Models\Document;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\LeadEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeadDocumentShare;
use Exception;

class LeadsController extends Controller
{
    /**
    * All Utils instance.
    *
    */
    protected $util;
    protected $lead_view;
    /**
    * Constructor
    *
    */
    public function __construct(Util $util)
    {
        $this->util = $util;
        $this->lead_view = ['list', 'kanban'];
    }

    public function index(Request $request)
    {
        $lead_view = empty($request->view) ? 'list' : (in_array($request->view, $this->lead_view) ? $request->view : 'list');
        $__global_clients_filter = $this->util->getGlobalClientsFilter();
        if(!empty($__global_clients_filter)) {
            $project_ids = $this->util->getClientsProjects($__global_clients_filter);
            $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
        } else {
            $project_ids = $this->util->getUserProjects(auth()->user());
            $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        }

        if ($request->ajax()) {

            $user = auth()->user();

            $query = $this->util->getFIlteredLeads($request);
            
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use($user) {
                $viewGate      = true;
                $editGate      = $user->is_superadmin;
                $deleteGate    = $user->is_superadmin;
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('email', function ($row) use($user) {
                $email_cell = $row->email ? $row->email : '';
                if(!empty($email_cell) && $user->is_channel_partner_manager) {
                    return maskEmail($email_cell);
                } else {
                    return $email_cell;
                }
            });

            $table->addColumn('overall_status', function ($row) {
                $overall_status = '';
                if($row->sell_do_is_exist){
                    $overall_status = '<b class="text-danger">Duplicate</b>';
                } else {
                    $overall_status = '<b class="text-success">New</b>';
                }
                return $overall_status;
            });

            $table->addColumn('sell_do_date', function ($row) {
                $date = '';
                if(!empty($row->sell_do_lead_created_at)){
                    $date = Carbon::parse($row->sell_do_lead_created_at)->format('d/m/Y');
                }
                return $date;
            });

            $table->addColumn('sell_do_time', function ($row) {
                $time = '';
                if(!empty($row->sell_do_lead_created_at)){
                    $time = Carbon::parse($row->sell_do_lead_created_at)->format('h:i A');
                }
                return $time;
            });

            $table->addColumn('sell_do_lead_id', function ($row) {
                $sell_do_lead_id = '';
                if(!empty($row->sell_do_lead_id)){
                    $sell_do_lead_id = $row->sell_do_lead_id;
                }
                return $sell_do_lead_id; 
            });

            $table->addColumn('phone', function ($row) use($user) {
                $phone =  $row->phone ? $row->phone : '';
                if(!empty($phone) && $user->is_channel_partner_manager) {
                    return maskNumber($phone);
                } else {
                    return $phone;
                }
            });

            $table->editColumn('secondary_phone', function ($row) use($user) {
                $secondary_phone =  $row->secondary_phone ? $row->secondary_phone : '';
                if(!empty($secondary_phone) && $user->is_channel_partner_manager) {
                    return maskNumber($secondary_phone);
                } else {
                    return $secondary_phone;
                }
            });

            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->addColumn('campaign_campaign_name', function ($row) {
                return $row->campaign ? $row->campaign->campaign_name : '';
            });

            $table->addColumn('source_name', function ($row) {
                return $row->source ? $row->source->name : '';
            });

            $table->addColumn('added_by', function ($row) {
                return $row->createdBy ? $row->createdBy->name : '';
            });

            $table->addColumn('created_at', function ($row) {
                return $row->created_at;
            });

            $table->addColumn('updated_at', function ($row) {
                return $row->updated_at;
            });

            $table->filter(function ($query) {
                $search = request()->get('search');
                $search_term = $search['value'] ?? '';
                if (request()->has('search') && !empty($search_term)) {
                    $query->where(function($q) use($search_term) {
                        $q->where('name', 'like', "%" . $search_term . "%")
                            ->orWhere('ref_num', 'like', "%" . $search_term . "%")
                            ->orWhere('sell_do_lead_id', 'like', "%" . $search_term . "%")
                            ->orWhere('email', 'like', "%" . $search_term . "%")
                            ->orWhere('additional_email', 'like', "%" . $search_term . "%")
                            ->orWhere('phone', 'like', "%" . $search_term . "%")
                            ->orWhere('secondary_phone', 'like', "%" . $search_term . "%");
                    });
                }
            });
            
            $table->rawColumns(['actions', 'email', 'phone', 'secondary_phone', 'placeholder', 'project', 'campaign', 'created_at', 'updated_at', 'source_name', 'added_by', 'overall_status', 'sell_do_date', 'sell_do_time', 'sell_do_lead_id']);

            return $table->make(true);
        }

        $projects  = Project::whereIn('id', $project_ids)
                        ->get();
        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->get();

        $sources = Source::whereIn('project_id', $project_ids)
                    ->whereIn('campaign_id', $campaign_ids)
                    ->get();

        if(in_array($lead_view, ['list'])) {
            return view('admin.leads.index', compact('projects', 'campaigns', 'sources', 'lead_view'));
        } else{
            $stage_wise_leads = $this->util->getFIlteredLeads($request)->get()->groupBy('sell_do_stage');
            $lead_stages = Lead::getStages();
            $filters = $request->except(['view']);
            return view('admin.leads.kanban_index', compact('projects', 'campaigns', 'sources', 'lead_view', 'stage_wise_leads', 'lead_stages', 'filters'));
        }
    }

    public function create()
    {
        if(!(auth()->user()->is_superadmin || auth()->user()->is_channel_partner)) {
            abort(403, 'Unauthorized.');
        }
        
        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user());

        $projects = Project::whereIn('id', $project_ids)
                        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $project_id = request()->get('project_id', null);

        return view('admin.leads.create', compact('campaigns', 'projects', 'project_id'));
    }

    public function store(StoreLeadRequest $request)
    {
        $input = $request->except(['_method', '_token']);
        $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details'] ?? []);
        $input['created_by'] = auth()->user()->id;

        /*
        * set default source if lead
        * added by channel partner
        */
        $source = Source::where('is_cp_source', 1)
                    ->where('project_id', $input['project_id'])
                    ->first();

        if(auth()->user()->is_channel_partner && !empty($source)) {
            $input['source_id'] = $source->id;
        }

        $lead = Lead::create($input);
        $lead->ref_num = $this->util->generateLeadRefNum($lead);
        $lead->save();

        $this->util->storeUniqueWebhookFields($lead);
        if(!empty($lead->project->outgoing_apis)) {
            $this->util->sendApiWebhook($lead->id);
        }
        
        return redirect()->route('admin.leads.index');
    }

    public function edit(Lead $lead)
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects = Project::whereIn('id', $project_ids)
                        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lead->load('project', 'campaign');

        return view('admin.leads.edit', compact('campaigns', 'lead', 'projects'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $input = $request->except(['_method', '_token']);
        $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details'] ?? []);

        $lead->update($input);
        $this->util->storeUniqueWebhookFields($lead);
        
        return redirect()->route('admin.leads.index');
    }

    public function show(Lead $lead)
    {
        if(
            auth()->user()->is_channel_partner && 
            ($lead->created_by != auth()->user()->id)
        ) {
            abort(403, 'Unauthorized.');
        }

        $lead->load('project', 'campaign', 'source', 'createdBy');

        $lead_events = LeadEvents::where('lead_id', $lead->id)
                        ->select('event_type', 'webhook_data', 'created_at as added_at', 'source')
                        ->orderBy('added_at', 'desc')
                        ->get();
        
        $project_ids = $this->util->getUserProjects(auth()->user());
        $projects_list = Project::whereIn('id', $project_ids)->pluck('name', 'id')
                            ->toArray();

        return view('admin.leads.show', compact('lead', 'lead_events', 'projects_list'));
    }

    public function destroy(Lead $lead)
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $lead->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadRequest $request)
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $leads = Lead::find(request('ids'));

        foreach ($leads as $lead) {
            $lead->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getLeadDetailHtml(Request $request)
    {
        if($request->ajax()) {
            $index = $request->get('index') + 1;
            if(empty($request->get('project_id'))) {
                return view('admin.leads.partials.lead_detail')
                    ->with(compact('index'));
            } else {
                $project = Project::findOrFail($request->get('project_id'));
                $webhook_fields = $project->webhook_fields ?? [];
                return view('admin.leads.partials.lead_detail')
                    ->with(compact('index', 'webhook_fields'));
            }
        }
    }

    public function getLeadDetailsKeyValuePair($lead_details_arr)
    {
        if(!empty($lead_details_arr)) {
            $lead_details = [];
            foreach ($lead_details_arr as $lead_detail) {
                if(isset($lead_detail['key']) && !empty($lead_detail['key'])) {
                    $lead_details[$lead_detail['key']] = $lead_detail['value'] ?? '';
                }
            }
            return $lead_details;
        }
        return [];
    }

    public function getLeadDetailsRows(Request $request)
    {
        if($request->ajax()) {

            $lead_details = [];
            $project_id = $request->input('project_id');
            $lead_id = $request->input('lead_id');
            $project = Project::findOrFail($project_id);
            $webhook_fields = $project->webhook_fields ?? [];
            
            if(!empty($lead_id)) {
                $lead = Lead::findOrFail($lead_id);
                $lead_details = $lead->lead_info;
            }

            $html = View::make('admin.leads.partials.lead_details_rows')
                        ->with(compact('webhook_fields', 'lead_details'))
                        ->render();

            return [
                'html' => $html,
                'count' => !empty($webhook_fields) ? count($webhook_fields) - 1 : 0
            ];
        }
    }

    public function sendMassWebhook(Request $request)
    {
        if($request->ajax()) {
            $lead_ids = $request->input('lead_ids');
            if(!empty($lead_ids)) {
                $response = [];
                foreach ($lead_ids as $id) {
                    $response = $this->util->sendApiWebhook($id);
                }
                return $response;
            }
        }
    }

    public function export(Request $request) 
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }
        
        return Excel::download(new LeadsExport($request), 'leads.xlsx');
    }

    public function shareDocument(Request $request, $lead_id, $doc_id)
    {
        $lead = Lead::findOrFail($lead_id);
        $document = Document::findOrFail($doc_id);
        $note = $request->input('note');
        try {
            $mails = [];
            if(!empty($lead->email)) {
                $mails[$lead->email] = $lead->name ?? $lead->ref_num;
            }

            if(!empty($lead->additional_email)) {
                $mails[$lead->additional_email] = $lead->name ?? $lead->ref_num;
            }

            if(!empty($mails)) {
                Notification::route('mail', $mails)->notify(new LeadDocumentShare($lead, $document, auth()->user(), $note));
                $this->util->logActivity($lead, 'document_sent', ['sent_by' => auth()->user()->id, 'document_id' => $doc_id, 'status' => 'sent', 'datetime' => Carbon::now()->toDateTimeString(), 'note' => $note]);
            }
            $output = ['success' => true, 'msg' => __('messages.success')];
        } catch (Exception $e) {
            $this->util->logActivity($lead, 'document_sent', ['sent_by' => auth()->user()->id, 'document_id' => $doc_id, 'status' => 'failed', 'datetime' => Carbon::now()->toDateTimeString(), 'note' => $note]);
            $output = ['success' => false, 'msg' => __('messages.something_went_wrong')];
        }
        return $output;
    }
}
