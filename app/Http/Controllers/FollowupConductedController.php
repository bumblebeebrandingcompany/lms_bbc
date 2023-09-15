<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FollowupConducted;
use Carbon\Carbon;
use Exception;
class FollowupConductedController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'payload_id',
                'payload_acted_on',
                'payload_agenda',
                'payload_campaign_info_name',
                'payload_campaign_info_id',
                'payload_campaign_info_medium_type',
                'payload_campaign_info_medium_value',
                'payload_campaign_info_source',
                'payload_campaign_info_sub_source',
                'payload_campaign_info_when',
                'payload_campaign_info_form_id',
                'payload_campaign_info_mcid',
                'payload_campaign_info_uuid',
                'payload_campaign_info_suid',
                'payload_campaign_info_rule_id',
                'payload_campaign_info_project_id',
                'payload_campaign_info_routing_request_id',
                'payload_campaign_info_forced_assignment',
                'payload_cancellation_reason',
                'payload_created_at',
                'payload_followup_type',
                'payload_scheduled_on',
                'payload_status',
                'payload_subject',
                'payload_initiated_by',
                'lead_id',
                'lead_first_name',
                'lead_last_name',
                'lead_email',
                'lead_phone',
                'event',
            ];

            $requestData = $request->only($fillableFields);
            $additionalDetails = $request->except($fillableFields);

            $requestData['payload_campaign_info_when'] = !empty($requestData['payload_campaign_info_when']) ? Carbon::parse($requestData['payload_campaign_info_when']) : null;
            $requestData['payload_created_at'] = !empty($requestData['payload_created_at']) ? Carbon::parse($requestData['payload_created_at']) : null;
            $requestData['payload_scheduled_on'] = !empty($requestData['payload_scheduled_on']) ? Carbon::parse($requestData['payload_scheduled_on']) : null;
            
            $followupConducted = new FollowupConducted();
            $followupConducted->fill($requestData);
            $followupConducted->additional_details = $additionalDetails;
            $followupConducted->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}
