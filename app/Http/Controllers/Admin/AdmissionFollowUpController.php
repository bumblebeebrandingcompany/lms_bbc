<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;



use App\Http\Requests\StoreFollowupRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Campaign;
use App\Models\Document;
use App\Models\Lead;
use App\Models\LeadEvents;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use App\Models\Followup;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DateTimeInterface;

class AdmissionFollowUpController extends Controller
{
    /**
     * All Utils instance.
     *
     */
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
        return view('admin.admissionfollowup.index', compact('campaigns', 'agencies', 'lead', 'followUps','projects','sources'));
    }

    public function store(StoreFollowupRequest $request)
    {
        $input = $request->validated();

        // Find the lead based on its ID
        $lead = Lead::findOrFail($input['lead_id']);

        // Check if the lead is not null before proceeding
        if ($lead) {
            $parentStageId = $request->input('stage_id');

            $followup = new Followup();
            $followup->lead_id = $lead->id;
            // $followup->user_id = $input['user_id'];
            $followup->followup_date = $input['follow_up_date'];
            $followup->followup_time = $input['follow_up_time'];
            $followup->notes = $input['notes'];
            $followup->stage_id = $parentStageId;
            $followup->save();

            // Check if $followup->lead is not null before updating
            if ($followup->lead) {
                $followup->lead->update(['stage_id' => $followup->stage_id]);
            }

            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
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
