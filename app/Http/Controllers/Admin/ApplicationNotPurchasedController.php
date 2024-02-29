<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Http\Requests\StoreFollowupRequest;

use App\Models\Lead;
use App\Models\User;

use App\Models\ApplicationPurchased;
use App\Utils\Util;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;

class ApplicationNotPurchasedController extends Controller
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
        $agencies = User::all();
        $user = auth()->user();
        if($user->is_superadmin)
        {
            $applications = ApplicationPurchased::all();
        }
        else
        {
            $applications = ApplicationPurchased::where('for_whom', auth()->id())->get();
        }

        return view('admin.application_not_purchased.index', compact('lead', 'applications', 'agencies'));
    }

    public function store(Request $request)
    {
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');
            $applicationpurchased = new ApplicationPurchased();
            $applicationpurchased->lead_id = $lead->id;
            $applicationpurchased->who_assigned = auth()->user()->id; // Store current user_id
            $applicationpurchased->for_whom = $request->input('user_id');
            $applicationpurchased->application_no = $request->input('application_no');
            $applicationpurchased->follow_up_date = $request->input('follow_up_date');
            $applicationpurchased->notes = $request->input('notes');
            $applicationpurchased->follow_up_time = $request->input('follow_up_time');
            $applicationpurchased->stage_id = $parentStageId;
            $applicationpurchased->lead->update(['user_id' => $applicationpurchased->for_whom]);
            $applicationpurchased->save();

            // Check if $admitted->lead is not null before updating
            if ($applicationpurchased->lead) {
                $applicationpurchased->lead->update(['stage_id' => $applicationpurchased->stage_id]);

                // Update the latest site visit as purchased
                $latestSiteVisit = $applicationpurchased->lead->siteVisits()->latest()->first();
                if ($latestSiteVisit) {
                    $latestSiteVisit->update(['stage_id' => $applicationpurchased->stage_id]);
                }
            }

            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }


}

