<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Http\Requests\StoreFollowupRequest;

use App\Models\Campaign;
use App\Models\Document;
use App\Models\Lead;
use App\Models\LeadEvents;
use App\Models\Project;
use App\Models\Source;
use App\Models\User;
use App\Models\ApplicationPurchased;
use App\Models\Admitted;
use App\Utils\Util;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;


class AdmittedController extends Controller
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
        $lead = Lead::all();
$admitteds=Admitted::all();

        $application=ApplicationPurchased::all();
        return view('admin.admitted.index', compact( 'lead','admitteds','application',));
    }
    public function store(Request $request)
    {
        // Find the lead based on its ID
        $lead = Lead::find($request->lead_id);
        // $application = ApplicationPurchased::find($request->application_id);
        // Check if the lead is not null before proceeding
        if ($lead) {
            $parentStageId = $request->input('parent_stage_id');
            $admitted = new Admitted();
            $admitted->lead_id = $lead->id;
            $admitted->application_id =$lead->application->id;

            $admitted->follow_up_date = $request->input('follow_up_date');
            $admitted->notes = $request->input('notes');
            $admitted->follow_up_time = $request->input('follow_up_time');
            $admitted->parent_stage_id = $parentStageId;

            $admitted->save();
            // Check if $admitted->lead is not null before updating
            $lead->parent_stage_id = $parentStageId;
            if ($admitted->lead) {
                $admitted->lead->update(['parent_stage_id' => $admitted->parent_stage_id]);
            }
            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }

}
