<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LeadsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLeadRequest;
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
use App\Notifications\LeadDocumentShare;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DateTimeInterface;

class FollowUpController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index(Request $request)
    {
        $lead = Lead::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $followUps = Followup::all();

        return view('admin.leads.followup.index', compact('campaigns', 'agencies', 'lead', 'followUps'));
    }

    public function store(StoreFollowupRequest $request)
    {

        $input = $request->validated(); // Use the validated input from the request
        // Find the lead and user based on their IDs
        $lead = Lead::findOrFail($input['lead_id']);
        // $user = User::findOrFail($input['user_id']);
        $followup = new Followup();
        $followup->lead_id = $lead->id;
        $followup->user_id = $input['user_id'];
        $followup->follow_up_date = $input['follow_up_date']; // Assign the date
        $followup->follow_up_time = $input['follow_up_time']; // Assign the time
        $followup->notes=$input['notes'];
        $followup->save();
        return redirect()->back()->with('success', 'Form submitted successfully!');
    }
    public function destroy(Followup $followup)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $followup->delete();

        return back();
    }
}
