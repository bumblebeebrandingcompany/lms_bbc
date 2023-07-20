<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCampaignRequest;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\Agency;
use App\Models\Campaign;
use App\Models\Project;
use Gate;
use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Util;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
class CampaignController extends Controller
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

            $query = Campaign::whereIn('id', $campaign_ids)
                        ->with(['project', 'agency'])->select(sprintf('%s.*', (new Campaign)->table));
                        
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use($user) {
                $viewGate      = true;
                $editGate      = true;
                $deleteGate    = $user->is_superadmin;
                $webhookSecretGate = $user->is_superadmin;
                $crudRoutePart = 'campaigns';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'webhookSecretGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('campaign_name', function ($row) {
                return $row->campaign_name ? $row->campaign_name : '';
            });

            $table->editColumn('source', function ($row) {
                return $row->source ? Campaign::SOURCE_SELECT[$row->source] : '';
            });
            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->addColumn('agency_name', function ($row) {
                return $row->agency ? $row->agency->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'project', 'agency']);

            return $table->make(true);
        }

        $projects = Project::whereIn('id', $project_ids)
                        ->get();
        $agencies = Agency::get();

        return view('admin.campaigns.index', compact('projects', 'agencies'));
    }

    public function create()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agencies = Agency::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.campaigns.create', compact('agencies', 'projects'));
    }

    public function store(StoreCampaignRequest $request)
    {
        
        $campaign_details = $request->except('_token');
        $campaign_details['webhook_secret'] = $this->util->generateWebhookSecret();
        $campaign = Campaign::create($campaign_details);

        return redirect()->route('admin.campaigns.index');
    }

    public function edit(Campaign $campaign)
    {
        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agencies = Agency::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaign->load('project', 'agency');

        return view('admin.campaigns.edit', compact('agencies', 'campaign', 'projects'));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        $campaign->update($request->all());

        return redirect()->route('admin.campaigns.index');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('project', 'agency', 'campaignLeads');

        return view('admin.campaigns.show', compact('campaign'));
    }

    public function destroy(Campaign $campaign)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $campaign->delete();

        return back();
    }

    public function massDestroy(MassDestroyCampaignRequest $request)
    {
        $campaigns = Campaign::find(request('ids'));

        foreach ($campaigns as $campaign) {
            $campaign->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function getWebhookDetails($id)
    {
        $campaign = Campaign::findOrFail($id);

        $lead =  Lead::where('campaign_id', $id)
                    ->latest()
                    ->first();
                    
        return view('admin.campaigns.webhook', compact('campaign', 'lead'));
    }

    public function saveOutgoingWebhookInfo(Request $request)
    {

        $id = $request->input('campaign_id');
        $webhook = $request->input('webhook');

        $campaign = Campaign::findOrFail($id);
        $campaign->outgoing_webhook = $webhook;
        $campaign->save();

        return redirect()->route('admin.campaigns.webhook', $campaign->id);
    }
}
