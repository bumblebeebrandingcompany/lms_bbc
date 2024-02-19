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

use App\Models\Clients;

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

        // Filter walkins where at least one lead was created by the authenticated user
        $walkins = $walkins->filter(function ($walkin) {
            return $walkin->leads->contains('created_by', auth()->id());
        });

        // Retrieve other necessary data
        $projects = Project::pluck('name', 'id');
        $client = Clients::all();
        $sources = Source::all();
        $campaign = Campaign::all();
        $projects = Project::all();
        $leads = Lead::all();

        return view('admin.walkinform.index', compact('walkins', 'client', 'sources', 'campaign', 'projects'));
    }


    // public function show(Walkin $cpwalkin)
    // {
    //     return view('admin.cpwalkins.show', compact('cpwalkin'));
    // }

    public function create()
    {
        if (!(auth()->user()->is_superadmin || auth()->user()->is_front_office)) {
            abort(403, 'Unauthorized.');
        }
        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user());

        $projects = Project::whereIn('id', $project_ids)
            ->pluck('name', 'id');
        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $project_id = request()->get('project_id', null);
        $sources = Source::all();
        $client = Clients::all();

        return view('admin.walkinform.create', compact('projects', 'project_ids', 'client', 'sources', 'campaigns', 'campaign_ids', 'project_id'));
    }
    public function store(Request $request)
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
        $existingLeads = Lead::where('phone', $input['phone'])->get();
    foreach ($existingLeads as $existingLead) {
        // Update each existing lead with the new data
        $existingLead->fill($input);
        // Save the updated lead
        $existingLead->save();
    }
        $lead->ref_num = $this->util->generateLeadRefNum($lead);
        $lead->save();
        $this->util->storeUniqueWebhookFields($lead);
        return redirect()->route('admin.walkinform.index')->with('success', 'Form created successfully');
    }
    public function edit(Walkin $walkinform)
    {
        if (!(auth()->user()->is_superadmin || auth()->user()->is_front_office)) {
            abort(403, 'Unauthorized.');
        }

        $user = auth()->user();
        $project_ids = $this->util->getUserProjects($user);
        $campaign_ids = $this->util->getCampaigns($user, $project_ids);

        $projects = Project::whereIn('id', $project_ids)
            ->pluck('name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->pluck('campaign_name', 'id')
            ->prepend(trans('global.pleaseSelect'), '');

        // Assuming you want to load leads related to the project and campaign of the given walkin
        $lead = Lead::where('project_id', $walkinform->project_id)
            ->where('campaign_id', $walkinform->campaign_id)
            ->get();

        return view('admin.walkinform.edit', compact('projects', 'campaigns', 'walkinform', 'lead'));
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


    public function destroy($id)
    {
        $walkinform = Walkin::findOrFail($id);
        $walkinform->delete();
        return redirect()->route('admin.walkinform.index')->with('success', 'Walkin deleted successfully');
    }
}
