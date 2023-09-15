<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteVisitScheduled;
use Carbon\Carbon;
use Exception;
class SiteVisitScheduledController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'id',
                'acted_on',
                'agenda',
                'cancellation_reason',
                'created_at',
                'followup_type',
                'scheduled_on',
                'status',
                'subject',
                'initiated_by',
                'lead_id',
                'lead_first_name',
                'lead_last_name',
                'lead_email',
                'lead_phone',
                'event',
            ];

            $requestData = $request->only($fillableFields);
            $additionalDetails = $request->except($fillableFields);

            $requestData['id_selldo'] = $requestData['id'] ?? null;
            $requestData['acted_on'] = !empty($requestData['acted_on']) ? Carbon::parse($requestData['acted_on']) : null;
            $requestData['created_at_sell_do'] = !empty($requestData['created_at']) ? Carbon::parse($requestData['created_at']) : null;
            $requestData['scheduled_on'] = !empty($requestData['scheduled_on']) ? Carbon::parse($requestData['scheduled_on']) : null;
            
            unset($requestData['id']);

            $siteVisitScheduled = new SiteVisitScheduled();
            $siteVisitScheduled->fill($requestData);
            $siteVisitScheduled->additional_details = $additionalDetails;
            $siteVisitScheduled->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('Site Visit Scheduled:- '.$msg);
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}

