<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Models\Walkin;
use App\Utils\Util;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Source;
use App\Http\Requests\WalkinStoreRequest;
use App\Models\Clients;
use Illuminate\Support\Facades\View;
class WalkinController extends Controller
{
    protected $util;

    /**
     * Constructor
     *
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }
    public function index()
    {
        // Get all walkins with their related leads
        $walkins = Walkin::with('leads')->get();

        $walkins = $walkins->filter(function ($walkin) {

            return $walkin->leads && $walkin->leads->contains('created_by', auth()->id());
        });

        // $user = auth()->user();
        // if($user->is_superadmin)
        // {
        //     $walkin = Walkin::all();
        // }
        // else
        // {
        //     $walkin = Walkin::where('created_by', auth()->id())->get();
        // }
        // Retrieve other necessary data
        $projects = Project::pluck('name', 'id');
        $clients = Clients::all();
        $sources = Source::all();
        $campaigns = Campaign::all();
        return view('admin.walkinform.index', compact('walkins', 'clients', 'sources', 'campaigns', 'projects'));
    }

    public function create()
    {
        if (!(auth()->user()->is_superadmin || auth()->user()->is_frontoffice)) {
            abort(403, 'Unauthorized.');
        }
        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user());
        $projects = Project::whereIn('id', $project_ids)
            ->pluck('name', 'id');
        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $project_id = request()->get('project_id', null);
        $excludedSourceIds = [13];
        $sources = Source::whereNotIn('id', $excludedSourceIds)->get();


        $client = Clients::all();
        $leads = Lead::all();

        return view('admin.walkinform.create', compact('projects', 'project_ids', 'client', 'sources', 'campaigns', 'campaign_ids', 'project_id','leads'));
    }
    public function store(WalkinStoreRequest $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'phone' => 'required|string|max:255',
        ]);

        $walkin = Walkin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'source_id' => $request->input('source_id'),
            'project_id' => $request->input('project_id'),
            'campaign_id' => $request->input('campaign_id'),
            'additional_email' => $request->input('additional_email'),
            'secondary_phone' => $request->input('secondary_phone'),
        ]);

        $lead = Lead::create([
            'walkin_id' => $walkin->id,
            'name' => $walkin->name,
            'email' => $walkin->email,
            'phone' => $walkin->phone,
            'source_id' => $walkin->source_id,
            'project_id' => $walkin->project_id,
            'campaign_id' => $walkin->campaign_id,
            'parent_stage_id' => 11,
            'created_by' => auth()->user()->id,
            'additional_email' => $request->additional_email,
            'secondary_phone' => $request->secondary_phone,
        ]);

        $input = $request->except(['_method', '_token']);
        // $existingLeads = Lead::where('phone', $input['phone'])->get();

        // foreach ($existingLeads as $existingLead) {
        //     // Update each existing lead with the new data
        //     $existingLead->fill($input);
        //     $existingLead->save();
        // }

        // $lead->ref_num = $this->util->generateLeadRefNum($lead);
        // $lead->save();
        // $this->util->storeUniqueWebhookFields($lead);

        // // Define $existingLeads as an empty array
        // $existingLeads = [];

        // if ($existingLead) {
        //     // You can access the 'ref_no' attribute
        //     $ref_num = $existingLead->ref_num;
            return view('admin.walkinform.create');
        // } else {
        //     return response()->json(['error' => 'Lead not found'], 404);
        // }
    }


    public function show($id)
    {
        $walkin = Walkin::findOrFail($id);

        return view('admin.walkinform.show', compact('walkin'));
    }


    public function update(Request $request, Walkin $walkinform)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'phone' => 'required|string|max:255',

        ]);

        $data = $request->only([
            'name',
            'email',
            'phone',
            'source_id',
            'project_id',
            'campaign_id',
            'additional_email',
            'secondary_phone',
        ]);

        $walkinform->update($data);
        if ($walkinform->leads()->exists()) {
            $lead = $walkinform->leads()->first();

            $lead->update($data);

            $this->util->storeUniqueWebhookFields($lead);
        }

        return redirect()->route('admin.walkinform.index')->with('success', 'Form updated successfully');
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
    public function destroy($id)
    {
        $walkinform = Walkin::findOrFail($id);
        $walkinform->delete();
        return redirect()->route('admin.walkinform.index')->with('success', 'Walkin deleted successfully');
    }
}

