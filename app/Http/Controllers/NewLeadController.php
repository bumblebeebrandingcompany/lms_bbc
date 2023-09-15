<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewLead;
use Carbon\Carbon;
use Exception;
class NewLeadController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'lead_id',
                'first_name',
                'last_name',
                'primary_email',
                'primary_phone',
                'secondary_phone',
                'received_on',
                'sales_name',
                'stage',
                'status',
                'hotness',
                'campaign_name',
                'team_name',
                'interested_properties',
                'generated_from',
                'campaign_source',
                'campaign_mt',
                'campaign_mv',
                'requirement_purpose',
            ];

            $requestData = $request->only($fillableFields);
            $additionalDetails = $request->except($fillableFields);

            $requestData['received_on'] = !empty($requestData['received_on']) ? Carbon::parse($requestData['received_on']) : null;
            
            $newLead = new NewLead();
            $newLead->fill($requestData);
            $newLead->additional_details = $additionalDetails;
            $newLead->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('New Lead:- '.$msg);
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}
