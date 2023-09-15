<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FollowupScheduled;
use Carbon\Carbon;
use Exception;
class FollowupScheduledController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'payload_id',
                'payload_acted_on',
                'payload_agenda',
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

            $requestData['payload_acted_on'] = !empty($requestData['payload_acted_on']) ? Carbon::parse($requestData['payload_acted_on']) : null;
            $requestData['payload_created_at'] = !empty($requestData['payload_created_at']) ? Carbon::parse($requestData['payload_created_at']) : null;
            $requestData['payload_scheduled_on'] = !empty($requestData['payload_scheduled_on']) ? Carbon::parse($requestData['payload_scheduled_on']) : null;
            
            $followupScheduled = new FollowupScheduled();
            $followupScheduled->fill($requestData);
            $followupScheduled->additional_details = $additionalDetails;
            $followupScheduled->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('Followup Scheduled:- '.$msg);
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}

