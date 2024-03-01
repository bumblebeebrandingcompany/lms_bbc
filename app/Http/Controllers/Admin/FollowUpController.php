<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowupRequest;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use App\Models\Followup;
use App\Utils\Util;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class FollowUpController extends Controller
{

    protected $util;
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index(Request $request)
    {
        $__global_clients_filter = $this->util->getGlobalClientsFilter();
        if (!empty($__global_clients_filter)) {
            $project_ids = $this->util->getClientsProjects($__global_clients_filter);
            $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
        } else {
            $project_ids = $this->util->getUserProjects(auth()->user());
            $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        }
        $lead = Lead::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $followUps = Followup::all();
        $itemsPerPage = request('perPage', 10);
        $followUps = Followup::paginate($itemsPerPage);
        $projects = Project::whereIn('id', $project_ids)
            ->get();
        $campaigns = Campaign::whereIn('id', $campaign_ids)
            ->get();

        $sources = Source::whereIn('project_id', $project_ids)
            ->whereIn('campaign_id', $campaign_ids)
            ->get();
        return view('admin.leads.followup.index', compact('campaigns', 'agencies', 'lead', 'followUps', 'projects', 'sources'));
    }
    public function store(StoreFollowupRequest $request)
    {

        $input = $request->validated();
        $lead = Lead::findOrFail($input['lead_id']);

        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $followup = new Followup();
            $followup->lead_id = $lead->id;
            $followup->followup_date = $input['follow_up_date'];
            $followup->followup_time = $input['follow_up_time'];
            $followup->notes = $input['notes'];
            $followup->created_by = auth()->user()->id;
            $followup->stage_id = $parentStageId;
            $followup->save();
            $this->logTimeline($lead, $$followup, 'Followup created', 'followup_created');
            if ($followup->lead) {
                $followup->lead->update(['stage_id' => $followup->stage_id]);
            }
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }
    public function destroy(Followup $followup)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $followup->delete();

        return back();
    }
}
