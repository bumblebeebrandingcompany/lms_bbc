<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteVisitConducted;
use Carbon\Carbon;
use Exception;
class SiteVisitConductedController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'id',
                'agenda',
                'campaign_info_name',
                'campaign_id',
                'campaign_medium_type',
                'campaign_medium_value',
                'campaign_source',
                'campaign_sub_source',
                'campaign_when',
                'sell_do_project_id',
                'ends_on',
                'status',
                'initiated_by',
                'product_name',
                'sv_conducted_on',
                'sv_conducted_by',
                'sv_conducted_by_id',
                'lead_id',
                'lead_first_name',
                'lead_last_name',
                'lead_email',
                'lead_phone',
                'sv_notes',
            ];

            $requestData = $request->only($fillableFields);
            $additionalDetails = $request->except($fillableFields);

            $requestData['id_selldo'] = $requestData['id'] ?? null;
            $requestData['project_id_selldo'] = $requestData['sell_do_project_id'] ?? null;
            $requestData['campaign_when'] = !empty($requestData['campaign_when']) ? Carbon::parse($requestData['campaign_when']) : null;
            $requestData['ends_on'] = !empty($requestData['ends_on']) ? Carbon::parse($requestData['ends_on']) : null;
            $requestData['sv_conducted_on'] = !empty($requestData['sv_conducted_on']) ? Carbon::parse($requestData['sv_conducted_on']) : null;

            unset($requestData['id'], $requestData['sell_do_project_id']);
            
            $siteVisitConducted = new SiteVisitConducted();
            $siteVisitConducted->fill($requestData);
            $siteVisitConducted->additional_details = $additionalDetails;
            $siteVisitConducted->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}

