<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Source;
use App\Utils\Util;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use App\Models\LeadEvents;
use App\Models\Lead;
use App\Models\User;

class WebhookReceiverController extends Controller
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

    /**
     * webhook coming during
     * form submission
     */
    public function processor(Request $request, $secret)
    {
        $source = Source::where('webhook_secret', $secret)
            ->firstOrFail();

        if (!empty($source) && !empty($request->all())) {
            $response = $this->util->createLead($source, $request->all());
            return response()->json($response['msg']);
        }

    }

    public function incomingWebhookList(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $new_leads_history = $this->__getLeadActivityHistory($request);
        $leads_activities_history = $this->__getNewLeadActivityHistory($request);

        return view('admin.webhook.index')
            ->with(compact('new_leads_history', 'leads_activities_history'));
    }

    /**
     * new lead coming
     * from sell.do
     */
    public function storeNewLead(Request $request)
    {
        try {
            $req_data = $request->all();

            if (empty($req_data)) {
                return response()->json(['message' => 'Request data is empty.'], 200);
            }

            if (isset($req_data['lead_id']) && !empty($req_data['lead_id'])) {
                $lead = $this->util->getLeadBySellDoLeadId($req_data['lead_id']);
                if (!empty($lead)) {
                    return response()->json(['message' => 'Lead is already present.'], 200);
                }
            }

            $details = [
                'name' => ($req_data['lead']['first_name'] ?? '') . ' ' . ($req_data['lead']['last_name'] ?? ''),
                'email' => $req_data['lead']['email'] ?? null,
                'phone' => $req_data['bbc_lms']['lead']['phone'] ?? null,
                'additional_email' => $req_data['payload']['secondary_emails'][0] ?? null,
                'secondary_phone' => $req_data['payload']['secondary_phones'][0] ?? null,
                'sell_do_lead_id' => $req_data['lead_id'] ?? null,
                'sell_do_is_exist' => 0,
                'sell_do_lead_created_at' => $req_data['payload']['recieved_on'] ?? null,
            ];

            // ... other details logic ...

            $lead = Lead::create($details);
            $lead->ref_num = $this->util->generateLeadRefNum($lead);
            $lead->parent_stage_id = 8;
            $lead->save();

            $this->util->storeUniqueWebhookFields($lead);

            return response()->json(['message' => __('messages.success'), 'lead' => $lead], 201);
        } catch (Exception $e) {
            $msg = 'File:' . $e->getFile() . ' | Line:' . $e->getLine() . ' | Message:' . $e->getMessage();
            \Log::info('store new lead:- ' . $msg);
            return response()->json(['message' => __('messages.something_went_wrong')], 404);
        }
    }

    public function handleServetelWebhook(Request $request)
    {
        // Parse the incoming webhook payload
        $payload = $request->all();
        if (empty($payload)) {
            return response()->json(['message' => 'Request data is empty.'], 200);
        }
        // Extract lead information from the payload
        $phoneNumber = $payload['caller_id_number']; // Caller ID number
        // $callToNumber = $payload['call_to_number']; // Number called by the caller
        // $startTimeStamp = $payload['start_stamp']; // Start timestamp of the call

        // Check if a lead with the provided phone number already exists
        $existingLead = Lead::where('phone', $phoneNumber)->first();

        if ($existingLead) {
            // Lead with this phone number already exists
            // Log the duplicate lead or handle as needed
            \Log::info('Duplicate lead received from Servetel: ' . json_encode($payload));
            return response()->json(['message' => 'Duplicate lead received'], 200);
        }

        // If lead doesn't exist, create a new lead
        Lead::create([
            'phone' => $phoneNumber,

        ]);

        // Respond to Servetel with a success message
        return response()->json(['message' => 'Lead created successfully'], 200);
    }

    protected function __getLeadActivityHistory()
    {
        $leads = Lead::whereNotNull('lead_event_webhook_response')
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(4);

        return $leads;
    }

    protected function __getNewLeadActivityHistory()
    {
        $activities = LeadEvents::with(['lead'])
            ->whereNotIn('event_type', ['document_sent'])
            ->select(['webhook_data', 'lead_id', 'sell_do_lead_id', 'created_at', 'event_type'])
            ->orderBy('created_at', 'desc')
            ->cursorPaginate(4);

        return $activities;
    }
}
