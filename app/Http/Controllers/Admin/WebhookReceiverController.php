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
                    
        if(!empty($source) && !empty($request->all())) {
            $response = $this->util->createLead($source, $request->all());
            return response()->json($response['msg']); 
        }
        
    }

    public function incomingWebhookList(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.webhook.index');
    }

    /**
    * new lead coming 
    * from sell.do
    */
    public function storeNewLead(Request $request)
    {
        try {
            $req_data = $request->all();

            if(empty($req_data)) {
                return response()->json(['message' => 'Request data is empty.'], 200);
            }

            if(isset($req_data['lead_id']) && !empty($req_data['lead_id'])){
                $lead = $this->util->getLeadBySellDoLeadId($req_data['lead_id']);
                if(!empty($lead))  return response()->json(['message' => 'Lead is already present.'], 200);
            }

            $details['name'] = ($req_data['lead']['first_name'] ?? '').' '.($req_data['lead']['last_name'] ?? '');
            $details['email'] = $req_data['lead']['email'] ?? null;
            $details['phone'] = $req_data['lead']['phone'] ?? null;
            $details['additional_email'] = $req_data['payload']['secondary_emails'][0]?? null;
            $details['secondary_phone'] = $req_data['payload']['secondary_phones'][0] ?? null;
            $details['sell_do_lead_id'] = $req_data['lead_id'] ?? null;
            $details['sell_do_is_exist'] = 0;

            $campaign_data = $req_data['payload']['campaign_responses'][0] ?? [];
            $project = $this->util->getProjectBySellDoProjectId($campaign_data);
            $details['project_id'] = !empty($project) ? $project->id : null;

            if(!empty($campaign_data['s']) && in_array($campaign_data['s'], ['channel_partner']) && !empty($project)) {
                $source = Source::where('is_cp_source', 1)
                        ->where('project_id', $project->id)
                        ->first();
                
                $details['source_id'] = $source->id ?? null;
            } else if(!empty($campaign_data['s']) && !in_array($campaign_data['s'], ['channel_partner']) && !empty($project)) {
                $source = Source::where('is_cp_source', 0)
                        ->where('project_id', $project->id)
                        ->where('source_name', 'like', '%'.$campaign_data['s'].'%')
                        ->first();
                $details['source_id'] = $source->id ?? null;
            }

            $details['campaign_id'] = null;
            

            $details['lead_details'] = [
                "age" => $req_data['payload']['age'] ?? '',
                "gender" => $req_data['payload']['gender'] ?? '',
                "married" => $req_data['payload']['married'] ?? '',
            ];
            $details['sell_do_stage'] = $req_data['payload']['stage'] ?? null;
            $details['sell_do_status'] = $req_data['payload']['status'] ?? null;
            $details['lead_event_webhook_response'] = $req_data;

            if(!empty($campaign_data['ss'])) {
                $user = User::where('name', 'like', '%'.$campaign_data['ss'].'%')
                        ->first();
                
                $details['created_by'] = $user->id ?? null;
            }

            $lead = Lead::create($details);
            $lead->ref_num = $this->util->generateLeadRefNum($lead);
            $lead->save();
            
            $this->util->storeUniqueWebhookFields($lead);

            return response()->json(['message' => __('messages.success'), 'lead' => $lead], 201);
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('store new lead:- '.$msg);
            return response()->json(['message' => __('messages.something_went_wrong')], 404);
        }
    }

    /**
    * lead activity coming from
    * sel.do
    */
    public function storeLeadActivity(Request $request)
    {
        try {
            $req_data = $request->all();

            if(empty($req_data)) {
                return response()->json(['message' => 'Request data is empty.'], 200);
            }

            if(isset($req_data['lead_id']) && !empty($req_data['lead_id'])){
                $lead = $this->util->getLeadBySellDoLeadId($req_data['lead_id']);
                $activity['lead_id'] = !empty($lead) ? $lead->id : null;
                if(!empty($lead) && !empty($req_data['event']) && in_array($req_data['event'], ['stage_changed'])) {
                    $lead->sell_do_stage = $req_data['payload']['stage'] ?? null;
                    $lead->sell_do_status = $req_data['payload']['status'] ?? null;
                    $lead->save();
                }
            }

            $activity['sell_do_lead_id'] = $req_data['lead_id'] ?? null;
            $activity['event_type'] = $req_data['event'] ?? null;
            $activity['webhook_data'] = $req_data;
            
            if(empty($activity['event_type'])) {
                return response()->json(['message' => 'Event is required.'], 404);
            }

            $event = LeadEvents::create($activity);
            
            return response()->json(['message' => __('messages.success'), 'event' => $event], 201);
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('store lead activity:- '.$msg);
            return response()->json(['message' => __('messages.something_went_wrong')], 404); 
        }
    }
}
