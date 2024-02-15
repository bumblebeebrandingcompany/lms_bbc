<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SiteVisitRequest;
use App\Models\Campaign;
use App\Models\Clients;
use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\SiteVisit;
use App\Models\ParentStage;
use App\Models\Source;
use App\Models\Tag;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Http\Request;

class SiteVisitController extends Controller
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
        $lead = Lead::all();
        $client = Clients::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $sitevisits = SiteVisit::all(); // Fixed variable name to $sitevisits
        $ids = $sitevisits->pluck('id'); // Get an array of all sitevisit IDs
        $parentStages = ParentStage::all();
        $tags = Tag::all();
        $sitevisitId =SiteVisit::all();
        $ids = [];
        foreach ($sitevisits as $sitevisit) {
            $ids[] = $sitevisit->id;
        }
        // $itemsPerPage = request('perPage', 10);
        // $sitevisits = SiteVisit::paginate($itemsPerPage);
        return view('admin.sitevisit.index', compact('campaigns', 'agencies', 'lead', 'sitevisits', 'ids', 'client','parentStages','tags'));
    }

    public function store(SiteVisitRequest $request)
    {
        $input = $request->validated(); // Use the validated input from the request

        // Find the lead and user based on their IDs
        $lead = Lead::findOrFail($input['lead_id']);
        $parentStageId = $request->input('parent_stage_id');

        $sitevisit = new SiteVisit();
        $sitevisit->lead_id = $lead->id;
        $sitevisit->follow_up_date = $input['follow_up_date']; // Assign the date
        $sitevisit->follow_up_time = $input['follow_up_time']; // Assign the time
        $sitevisit->notes = $input['notes'];
        $sitevisit->parent_stage_id = $parentStageId;
        $sitevisit->save();

        $sitevisit->lead->update(['parent_stage_id' => $sitevisit->parent_stage_id]);

        // Log the timeline
        $this->logTimeline($lead,$sitevisit, 'Site Visit created', 'sitevisit_created');

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    private function logTimeline(Lead $lead, $sitevisit, $type, $description)
    {
        $timeline = new LeadTimeline;
        $timeline->activity_type = $type;
        $timeline->lead_id = $lead->id;

        // Combine lead and site visit data into payload
        $payload = [
            'lead' => $lead->toArray(),
            'sitevisit' => $sitevisit->toArray()
        ];

        $timeline->payload = json_encode($payload); // Convert array to JSON
        $timeline->description = $description;
        $timeline->save();
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'follow_up_date' => 'date',
            'follow_up_time' => 'date_format:H:i',
            'deleted_at' => 'date',
            'lead_id' => [
                'required',
                'integer',
            ],
            'notes' => 'nullable|string',
        ]);

        // Find the original SiteVisit record by ID
        $originalSiteVisit = SiteVisit::find($id);
        if (!$originalSiteVisit) {
            return redirect()->back()->with('error', 'Site visit not found.');
        }

        $parentStageId = $request->input('parent_stage_id');

        // Update the old schedule's parent_stage_id to 19
        $originalSiteVisit->update(['parent_stage_id' => 19]);

        // Create a new SiteVisit
        $newSiteVisit = new SiteVisit();
        $newSiteVisit->follow_up_date = $request->follow_up_date;
        $newSiteVisit->follow_up_time = $request->follow_up_time;
        $newSiteVisit->lead_id = $request->lead_id;
        // $newSiteVisit->user_id = $request->user_id;
        $newSiteVisit->notes = $request->notes;
        $newSiteVisit->parent_stage_id = $parentStageId; // Set parent_stage_id directly
        // $newSiteVisit->logTimeline('rescheduled', 'rescheduled');
        $newSiteVisit->save();

        // Update the lead status to "rescheduled"
        $lead = Lead::find($request->lead_id);
        if ($lead) {
            $lead->update(['parent_stage_id' => 19]);
        }
        return redirect()->back()->with('success', 'Site visit rescheduled successfully.');
    }

    public function cancelSiteVisit(Request $request, $sitevisitId)
    {
        $sitevisit = SiteVisit::findOrFail($sitevisitId);
        $lead = Lead::find($request->lead_id);

        $parentStageId = $request->input('parent_stage_id');
        $sitevisit->update(['parent_stage_id' => $parentStageId]);
        // $sitevisit->logTimeline($lead->id, 'sitevisit cancelled', 'site_visit_cancelled', $sitevisit->id);
        $sitevisit->lead->update(['parent_stage_id' => $sitevisit->parent_stage_id]);


        return redirect()->back();

    }


    public function conducted(Request $request, $sitevisitId)
    {
        $sitevisit = SiteVisit::findOrFail($sitevisitId);

        // Validate the request data
        $request->validate([
            'notes' => 'required|string',
        ]);
        // Update the site visit information

        $sitevisit->update([
            'parent_stage_id' => $request->input('parent_stage_id'),
            'notes' => $request->input('notes'),

        ]);

        // Update the lead information if it's associated with the site visit
        if ($sitevisit->lead) {
            $sitevisit->lead->update([
                'parent_stage_id' => $sitevisit->parent_stage_id,
                // 'notes' => $sitevisit->notes,
                // Add other lead fields you want to update
            ]);
        }
        // Redirect or return a response as needed
        return redirect()->back()->with('success', 'Site visit conducted successfully!');
    }
    public function applicationpurchased(Request $request, $sitevisitId)
    {

        $sitevisit = SiteVisit::findOrFail($sitevisitId);

        // Validate the request data
        // $request->validate([
        //     'notes' => 'required|string',
        // ]);
        // Update the site visit information
        $sitevisit->update([
            'parent_stage_id' => $request->input('parent_stage_id'),
            'notes' => $request->input('notes'),
            'user_id'=>$request->input('user_id'),
            'application_no'=>$request->input('application_no'),

        ]);
      if ($sitevisit->lead) {
            $sitevisit->lead->update([
                'parent_stage_id' => $sitevisit->parent_stage_id,
                'user_id' => $request->input('user_id'),
               'application_no' =>$sitevisit->application_no,
                 'notes'=>$sitevisit->notes,
            ]);
        }
        // Redirect or return a response as needed
        return redirect()->back()->with('success', 'application purchased successfully!');
    }
    public function notVisited(Request $request, $sitevisitId)
    {
        $sitevisit = SiteVisit::findOrFail($sitevisitId);
        $parentStageId = $request->input('parent_stage_id');
        $lead = Lead::find($request->lead_id);
        $sitevisit->update([
            'parent_stage_id' => $parentStageId,
            'notes' => $request->input('notes'),
        ]);
        $sitevisit->lead->update(['parent_stage_id' => $sitevisit->parent_stage_id,
        'notes'=>$sitevisit->notes,
    ]);
        // $sitevisit->logTimeline($lead->id, 'Site not visited', 'site_not_visited', $sitevisit->id);
        return redirect()->back();
    }
}
