<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CallActivity;
use Carbon\Carbon;
use Exception;
class CallActivityController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'id',
                'created_at',
                'updated_at',
                'booking_detail_id',
                'c2c',
                'called_on',
                'campaign_info_name',
                'campaign_info_id',
                'campaign_info_medium_type',
                'campaign_info_medium_value',
                'campaign_info_source',
                'campaign_info_sub_source',
                'campaign_info_when',
                'campaign_info_form_id',
                'campaign_info_mcid',
                'campaign_info_uuid',
                'campaign_info_suid',
                'campaign_info_rule_id',
                'campaign_info_project_id',
                'campaign_info_routing_request_id',
                'campaign_info_forced_assignment',
                'created_at_sell_do',
                'direction',
                'duration',
                'feedback',
                'message',
                'notes',
                'offline',
                'originator',
                'recipient',
                'recording_url',
                'remote_file',
                'remote_id',
                'status',
                'updated_at_sell_do',
                'initiated_by',
                'lead_id',
                'lead_first_name',
                'lead_last_name',
                'lead_email',
                'lead_phone',
                'event'
            ];

            $requestData = $request->only($fillableFields);
            $additionalDetails = $request->except($fillableFields);

            $requestData['id_sell_do'] = $requestData['id'] ?? null;
            $requestData['created_at_sell_do'] = !empty($requestData['created_at']) ? Carbon::parse($requestData['created_at']) : null;
            $requestData['updated_at_sell_do'] = !empty($requestData['updated_at']) ? Carbon::parse($requestData['updated_at']) : null;
            $requestData['campaign_info_when'] = !empty($requestData['campaign_info_when']) ? Carbon::parse($requestData['campaign_info_when']) : null;
            
            unset($requestData['id'], $requestData['created_at'], $requestData['updated_at']);

            $callActivity = new CallActivity();
            $callActivity->fill($requestData);
            $callActivity->additional_details = $additionalDetails;
            $callActivity->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('Call Activity:- '.$msg);
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}
