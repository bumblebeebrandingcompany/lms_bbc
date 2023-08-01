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
class LeadsController extends Controller
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
        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        if ($request->ajax()) {

            $user = auth()->user();

            $query = Lead::with(['project', 'campaign', 'source', 'createdBy'])
                        ->select(sprintf('%s.*', (new Lead)->table));

            $query = $query->where(function ($q) use($project_ids, $campaign_ids, $user) {
                if($user->is_channel_partner) {
                    $q->where('leads.created_by', $user->id);
                } else {
                    $q->whereIn('leads.project_id', $project_ids)
                        ->orWhereIn('leads.campaign_id', $campaign_ids);
                }
            })->groupBy('id');
            

            //filter leads
            if(!empty($request->input('project_id'))) {
                $query->where('leads.project_id', $request->input('project_id'));
            }

            if(!empty($request->input('campaign_id'))) {
                $query->where('leads.campaign_id', $request->input('campaign_id'));
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use($user) {
                $viewGate      = true;
                $editGate      = true;
                $deleteGate    = $user->is_superadmin || $user->is_channel_partner;
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });

            $table->addColumn('phone', function ($row) {
                return $row->phone ? $row->phone : '';
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

            $table->rawColumns(['actions', 'email', 'phone', 'placeholder', 'project', 'campaign', 'created_at', 'updated_at', 'source_name', 'added_by']);

            return $table->make(true);
        }

        $projects  = Project::whereIn('id', $project_ids)
                        ->get();
        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->get();

        return view('admin.leads.index', compact('projects', 'campaigns'));
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
        $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details']);
        $input['created_by'] = auth()->user()->id;

        $lead = Lead::create($input);

        $source = null;
        if(auth()->user()->is_channel_partner) {
            $source = Source::where('project_id', $lead->project_id)
                    ->first();
        }
        // $this->util->sendWebhook($lead->id);
        $this->util->sendApiWebhook($lead->id, $source);
        return redirect()->route('admin.leads.index');
    }

    public function edit(Lead $lead)
    {
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
        $input['lead_details'] = $this->getLeadDetailsKeyValuePair($input['lead_details']);

        $lead->update($input);

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

        return view('admin.leads.show', compact('lead'));
    }

    public function destroy(Lead $lead)
    {
        if(!(auth()->user()->is_superadmin || auth()->user()->is_channel_partner)) {
            abort(403, 'Unauthorized.');
        }

        $lead->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadRequest $request)
    {
        if(!(auth()->user()->is_superadmin || auth()->user()->is_channel_partner)) {
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
            return view('admin.leads.partials.lead_detail')
                ->with(compact('index'));
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
}
