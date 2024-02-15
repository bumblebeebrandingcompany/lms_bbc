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

        $walkins = Walkin::with('leads')->get();
        $projects = Project::pluck('name', 'id');
        $client=Clients::all();
        $sources=Source::all();
        $campaign=Campaign::all();
        $projects=Project::all();
        $leads=Lead::all();
        return view('admin.walkinform.index', compact('walkins','client','sources','campaign','projects'));
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
            ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $project_id = request()->get('project_id', null);
       $sources=Source::all();
        $client=Clients::all();

        return view('admin.walkinform.create', compact('projects', 'project_ids','client','sources','campaigns','campaign_ids','project_id'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string',
            'phone' => 'required|string|max:255',
'source_id',
'campaign_id',
'project_id',
'additional_email',
'secondary_phone',
        ]);
        $walkin = Walkin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'source_id' => $request->input('source_id'),
            'project_id' => $request->input('project_id'),
            'campaign_id' => $request->input('campaign_id'),
            'additional_email'=>$request->input('additional_email'),
            'secondary_phone'=>$request->input('secondary_phone'),
        ]);
        $lead=Lead::create([
           'walkin_id'=>$walkin->id,
            'name' => $walkin->name,
            'email' => $walkin->email,
            'phone' => $walkin->phone,
            'source_id' => $walkin->source_id,
            'project_id' => $walkin->project_id,
            'campaign_id' => $walkin->campaign_id,
            'additional_email'=>$request->additional_email,
            'secondary_phone'=>$request->secondary_phone,
        ]);
        $lead->ref_num = $this->util->generateLeadRefNum($lead);
        $lead->save();
        $this->util->storeUniqueWebhookFields($lead);
        // if(!empty($lead->project->outgoing_apis)) {
        //     $this->util->sendApiWebhook($lead->id);
        // }
        // if(!empty($request->get('redirect_to')) && $request->get('redirect_to') == 'ceoi') {
        //     return redirect()->route('admin.eoi.create', ['phone' => $lead->phone]);
        // }
        return redirect()->route('admin.walkinform.index')->with('success', 'Form created successfully');
    }
public function edit(Walkin $walkinform)
{
    if (!(auth()->user()->is_superadmin || auth()->user()->is_front_office)) {
        abort(403, 'Unauthorized.');
    }


    $project_ids = $this->util->getUserProjects(auth()->user());
    $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

    $projects = Project::whereIn('id', $project_ids)
        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
    $campaigns = Campaign::whereIn('id', $campaign_ids)
        ->pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $lead=Lead::all();
    $lead->load('project', 'campaign');
    $walkins=Walkin::all();
    return view('admin.walkinform.edit', compact('projects','campaigns','walkinform','walkins'));
}
    public function show(Walkin $walkin)
{
    $walkins = Walkin::all();
    return view('admin.walkinform.show', compact('walkin','walkins'));
}
public function update(Request $request, Walkin $walkinform)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string',
        'phone' => 'required|string|max:255',

    ]);

    $data = $request->only([
        'name', 'email', 'phone', 'source_id', 'project_id', 'campaign_id',
        'additional_email', 'secondary_phone',
    ]);

    $walkinform->update($data);

    // Check if the 'leads' relationship is defined in the 'Walkin' model
    if ($walkinform->leads()->exists()) {
        $lead = $walkinform->leads()->first();

        $lead->update($data);

        // Assuming the 'util' property is accessible and has the 'storeUniqueWebhookFields' method
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

