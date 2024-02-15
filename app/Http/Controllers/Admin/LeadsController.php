<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\SiteVisit;
use App\Models\Followup;
use App\Models\CallRecord;
use App\Models\Project;
use App\Models\Clients;
use App\Models\Agency;
use App\Models\Note;
use App\Models\ParentStage;
use App\Models\Tag;
use App\Models\NoteNotInterested;

use Gate;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use App\Models\Source;
use App\Models\Stage;
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

    public function initiateCall(Lead $lead)
    {

        $destination_number = $lead->phone;
        $agent_number = '+919677222567';

        $apiKey = config('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNjk4NjY0NTAxLCJleHAiOjE2OTg2NjgxMDEsIm5iZiI6MTY5ODY2NDUwMSwianRpIjoiaFBDRUIwblllUjBjU2N2MCIsInN1YiI6IjE3MDQ0MCJ9.V_qQ_Vtm9d2ojWyqR1ZBfxjQRt2JJnz3YHXgXJ3WIxQ');
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxNzA0NDAiLCJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL3Rva2VuL2dlbmVyYXRlIiwiaWF0IjoxNjk4NjYxMjYwLCJleHAiOjE5OTg2NjEyNjAsIm5iZiI6MTY5ODY2MTI2MCwianRpIjoiTWtYY0h0OXlpNG5Ea2FuaSJ9.L23vhUJ0UIGc3nffLeMK0NMczroLgwwkECFnaCaY-A8';

        $baseURL = 'https://api.servetel.in/v1';
        $client = new Client();

        $requestBody = [
            "agent_number" => '+919677222567',
            "destination_number" => $lead->phone,
        ];
        $response = $client->post("$baseURL/click_to_call", [
            'headers' => [
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
            ],
            'json' => $requestBody,
        ]);

        if ($response->getStatusCode() == 200) {
            $responseBody = $response->getBody()->getContents();
        } else {
            $errorResponse = json_decode($response->getBody()->getContents(), true);
        }

        return back();
    }

    public function index(Request $request)
    {
        $lead_view = empty($request->view) ? 'list' : (in_array($request->view, $this->lead_view) ? $request->view : 'list');
        $__global_clients_filter = $this->util->getGlobalClientsFilter();
        if (!empty($__global_clients_filter)) {
            $project_ids = $this->util->getClientsProjects($__global_clients_filter);
            $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
        } else {
            $project_ids = $this->util->getUserProjects(auth()->user());
            $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        }

        if ($request->ajax()) {

            $user = auth()->user();
            $lead_stage = '';
            if ($user->is_agency || $user->is_superadmin || $user->is_presales) {
                $lead_stage = ['Site Visit Scheduled', 'Site Visit Conducted', 'enquiry', 'application purchased', 'lost', 'followup', 'rescheduled', 'Site Not Visited', 'Admitted', 'Spam', 'Not Qualified', 'Future Prospect', 'Cancelled', 'RNR', 'virtual call scheduled', 'Virtual Call Conducted', 'virtual call cancelled' . 'Admission FollowUp'];
            } elseif ($user->is_client || $user->is_frontoffice) {

                $lead_stage = ['Site Visit Scheduled', 'Site Visit Conducted', 'Cancelled'];
            } elseif ($user->is_admissionteam) {
                $lead_stage = ['Admission FollowUp', 'application purchased', 'admitted'];
            }

            $query = $this->util->getFIlteredLeads($request);

            $query->where(function ($query) use ($lead_stage, $user) {
                $query->whereHas('parentStage', function ($q) use ($lead_stage) {
                    $q->whereIn('name', $lead_stage);
                });

                if ($user->is_admissionteam) {
                    // If the user is part of the admission team, filter by user_id
                    $query->where('user_id', $user->id);
                } elseif ($user->is_superadmin || $user->is_frontoffice) {
                    // If the user is super admin or front office, include leads with empty parent_stage_id
                    $query->orWhereNull('parent_stage_id');
                }
            });
            $table = Datatables::of($query);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use ($user) {
                $viewGate = true;
                $editGate = $user->is_superadmin;
                $deleteGate = $user->is_superadmin;
                $crudRoutePart = 'leads';

                return view(
                    'partials.datatablesActions',
                    compact(
                        'viewGate',
                        'editGate',
                        'deleteGate',
                        'crudRoutePart',
                        'row'
                    )
                );
            });

            $table->addColumn('email', function ($row) use ($user) {
                $email_cell = $row->email ? $row->email : '';
                if (!empty($email_cell) && $user->is_channel_partner_manager) {
                    return maskEmail($email_cell);
                } else {
                    return $email_cell;
                }
            });

            $table->addColumn('overall_status', function ($row) {
                $overall_status = '';
                if ($row->sell_do_is_exist) {
                    $overall_status = '<b class="text-danger">Duplicate</b>';
                } else {
                    $overall_status = '<b class="text-success">New</b>';
                }
                return $overall_status;
            });

            $table->addColumn('sell_do_date', function ($row) {
                $date = '';
                if (!empty($row->sell_do_lead_created_at)) {
                    $date = Carbon::parse($row->sell_do_lead_created_at)->format('d/m/Y');
                }
                return $date;
            });

            $table->addColumn('sell_do_time', function ($row) {
                $time = '';
                if (!empty($row->sell_do_lead_created_at)) {
                    $time = Carbon::parse($row->sell_do_lead_created_at)->format('h:i A');
                }
                return $time;
            });

            $table->addColumn('sell_do_lead_id', function ($row) {
                $sell_do_lead_id = '';
                if (!empty($row->sell_do_lead_id)) {
                    $sell_do_lead_id = $row->sell_do_lead_id;
                }
                return $sell_do_lead_id;
            });

            $table->addColumn('phone', function ($row) use ($user) {
                $phone = $row->phone ? $row->phone : '';
                if (!empty($phone) && $user->is_channel_partner_manager) {
                    return maskNumber($phone);
                } else {
                    return $phone;
                }
            });

            $table->editColumn('secondary_phone', function ($row) use ($user) {
                $secondary_phone = $row->secondary_phone ? $row->secondary_phone : '';
                if (!empty($secondary_phone) && $user->is_channel_partner_manager) {
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
                    $query->where(function ($q) use ($search_term) {
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

        $projects = Project::whereIn('id', $project_ids)
            ->get();
        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->get();

        $sources = Source::whereIn('project_id', $project_ids)
            ->whereIn('campaign_id', $campaign_ids)
            ->get();

        $leads = Lead::all();
        if (in_array($lead_view, ['list'])) {
            return view('admin.leads.index', compact('projects', 'campaigns', 'sources', 'lead_view', 'leads'));
        } elseif ($lead_view === 'kanban') {
            $user = auth()->user();
            $lead_stage = '';
            if ($user->is_agency || $user->is_superadmin || $user->is_presales) {
                $lead_stage = ['Site Visit Scheduled', 'Site Visit Conducted', 'enquiry', 'application purchased', 'lost', 'followup', 'rescheduled', 'Site Not Visited', 'Admitted', 'Spam', 'Not Qualified', 'Future Prospect', 'Cancelled', 'RNR', 'virtual call scheduled', 'Virtual Call Conducted', 'virtual call cancelled' . 'Admission FollowUp'];
            } elseif ($user->is_client || $user->is_frontoffice) {

                $lead_stage = ['Site Visit Scheduled', 'Site Visit Conducted', 'Cancelled'];
            } elseif ($user->is_admissionteam) {

                $lead_stage = ['Admission FollowUp', 'application purchased', 'admitted'];




            }

            $query = $this->util->getFIlteredLeads($request);
            $query->where(function ($query) use ($lead_stage, $user) {
                $query->whereHas('parentStage', function ($q) use ($lead_stage) {
                    $q->whereIn('name', $lead_stage);
                });

                if ($user->is_admissionteam) {
                    // If the user is part of the admission team, filter by user_id
                    $query->where('user_id', $user->id);
                } elseif ($user->is_superadmin || $user->is_frontoffice) {
                    // If the user is super admin or front office, include leads with empty parent_stage_id
                    $query->orWhereNull('parent_stage_id');
                }
            });
            $stage_wise_leads = $query->get()->groupBy('parent_stage_id');
            $lead_stages = Lead::getStages();
            $filters = $request->except(['view']);
            return view('admin.leads.kanban_index', compact('projects', 'campaigns', 'sources', 'lead_view', 'stage_wise_leads', 'lead_stages', 'filters', 'leads'));
        }
    }
    public function create()
    {
        if (!(auth()->user()->is_superadmin || auth()->user()->is_channel_partner)) {
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

    $input['parent_stage_id'] = $request->input('parent_stage_id');
    $input['user_id'] = $request->input('user_id');
    $existingLeads = Lead::where('phone', $input['phone'])->get();

    foreach ($existingLeads as $existingLead) {
        // Update each existing lead with the new data
        $existingLead->fill($input);

        // Save the updated lead
        $existingLead->save();
        $this->logTimeline($existingLead, 'lead_recreated', 'Lead Recreated Again');
    }

    if ($existingLeads->isEmpty()) {
        // Create a new lead if no existing leads are found
        $lead = Lead::create($input);
        $lead->ref_num = $this->util->generateLeadRefNum($lead);
        $lead->save();
        $this->logTimeline($lead, 'lead_created', 'Lead Created Successfully');

        // Update source and campaign for the new lead if necessary
        if (auth()->user()->is_channel_partner) {
            $source = Source::where('is_cp_source', 1)
                ->where('project_id', $input['project_id'])
                ->first();

            if (!empty($source)) {
                $lead->source_id = $source->id;
                $lead->campaign = $source->campaign;
                $lead->save();
            }
        }

        $this->util->storeUniqueWebhookFields($lead);

        if (!empty($lead->project->outgoing_apis)) {
            $this->util->sendApiWebhook($lead->id);
        }
    }

    return redirect()->route('admin.leads.index');
}


    public function showTimeline($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $allActivities = $this->getLeadActivities($lead);

        return view('leads.timeline', compact('lead', 'allActivities'));
    }
    public function edit(Lead $lead)
    {
        if (!auth()->user()->is_superadmin) {
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
    private function logTimeline(Lead $lead, $type, $description)
    {
        $timeline = new LeadTimeline;
        $timeline->activity_type = $type;
        $timeline->lead_id = $lead->id;
        $timeline->payload = json_encode($lead->toArray()); // Convert array to JSON
        $timeline->description = $description;
        $timeline->save();
    }



    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $input = $request->except(['_method', '_token']);
        $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details'] ?? []);

        // Ensure that 'stage_id' is present in the $input array
        $input['parent_stage_id'] = $request->input('parent_stage_id');

        // Log the update of 'parent_stage_id'
        $this->logTimeline($lead, 'Stage Changed', "Stage was updated to {$input['parent_stage_id']}");

        // Log the general update

        // Update the 'parent_stage_id' directly without any authorization checks
        $lead->update($input);

        // Assuming you have a method like storeUniqueWebhookFields
        $this->util->storeUniqueWebhookFields($lead);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function show(Lead $lead, Request $request)
    {
        if (
            auth()->user()->is_channel_partner &&
            ($lead->created_by != auth()->user()->id)
        ) {
            abort(403, 'Unauthorized.');
        }

        $lead->load('project', 'campaign', 'source', 'createdBy');
        $timelineItems = LeadTimeline::where('lead_id', $lead->id)->orderBy('created_at', 'asc')->get();
        $lead_events = LeadEvents::where('lead_id', $lead->id)
            ->select('event_type', 'webhook_data', 'created_at as added_at', 'source')
            ->orderBy('added_at', 'desc')
            ->get();

        $project_ids = $this->util->getUserProjects(auth()->user());
        $projects_list = Project::whereIn('id', $project_ids)->pluck('name', 'id')
            ->toArray();

        //   $lead->parentStages()->sync($request->interested_stages);

        $parentStages = ParentStage::all();
        $stages = Stage::all();
        $tags = Tag::all();
        $leads = Lead::all();
        $sitevisits = SiteVisit::all();
        $allActivities = $this->getLeadActivities($lead);
        $noteNotInterested = NoteNotInterested::all();
        $client = Clients::all();
        $lead->load('project', 'campaign', 'source', 'createdBy');
        $agencies = Agency::all();
        $user_id = request()->get('user_id'); // Get the user ID from the request

        // Load follow-ups associated with the lead and filter by user ID
        $followUps = Followup::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->get();
        $callRecords = CallRecord::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->get();
        $sitevisit = SiteVisit::where('lead_id', $lead->id)
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->get();
        $note = Note::where('lead_id', $lead->id)->when($user_id, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        })->get();
        $itemsPerPage = request('perPage', 10);
        // $followUps = Followup::paginate($itemsPerPage);
        $notes = Note::paginate($itemsPerPage);

        //   $note = Note::where('lead_id', $lead->id)
        //       ->when($user_id, function ($query) use ($user_id) {
        //           return $query->where('user_id', $user_id);
        //       })
        //       ->get();
//     $followUps = $followUps->filter(function ($followUp) {
//     return $followUp->parent_stage_id == 28 || $followUp->parent_stage_id == 9;
// });
        $sitevisits = SiteVisit::all();
        $campaigns = Campaign::all();
        return view('admin.leads.show', compact('lead', 'lead_events', 'timelineItems', 'projects_list', 'parentStages', 'stages', 'tags', 'agencies', 'user_id', 'followUps', 'campaigns', 'sitevisit', 'client', 'leads', 'note', 'sitevisits', 'callRecords', 'notes', 'allActivities', 'noteNotInterested'));
    }
    public function destroy(Lead $lead)
    {
        if (!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $lead->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadRequest $request)
    {
        if (!auth()->user()->is_superadmin) {
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
        if ($request->ajax()) {
            $index = $request->get('index') + 1;
            if (empty($request->get('project_id'))) {
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
        if (!empty($lead_details_arr)) {
            $lead_details = [];
            foreach ($lead_details_arr as $lead_detail) {
                if (isset($lead_detail['key']) && !empty($lead_detail['key'])) {
                    $lead_details[$lead_detail['key']] = $lead_detail['value'] ?? '';
                }
            }
            return $lead_details;
        }
        return [];
    }

    public function getLeadDetailsRows(Request $request)
    {
        if ($request->ajax()) {

            $lead_details = [];
            $project_id = $request->input('project_id');
            $lead_id = $request->input('lead_id');
            $project = Project::findOrFail($project_id);
            $webhook_fields = $project->webhook_fields ?? [];

            if (!empty($lead_id)) {
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
        if ($request->ajax()) {
            $lead_ids = $request->input('lead_ids');
            if (!empty($lead_ids)) {
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
        if (!auth()->user()->is_superadmin) {
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
            if (!empty($lead->email)) {
                $mails[$lead->email] = $lead->name ?? $lead->ref_num;
            }

            if (!empty($lead->additional_email)) {
                $mails[$lead->additional_email] = $lead->name ?? $lead->ref_num;
            }

            if (!empty($mails)) {
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

    private function getLeadActivities(Lead $lead)
    {
        $leadActivities = $lead->timeline()->get();
        // $noteActivities = $lead->notes()->with('activity')->get();
        // $callRecordActivities = $lead->callRecords()->with('activity')->get();
        // $siteVisitActivities = $lead->siteVisits()->with('activity')->get();

        return collect([])
            ->merge($leadActivities)

            ->sortByDesc('created_at');
    }
}
