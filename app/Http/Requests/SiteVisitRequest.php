<?php

namespace App\Http\Requests;

use App\Models\Sitevisit;
use Illuminate\Foundation\Http\FormRequest;

use Gate;
use Illuminate\Foundation\Http\UpdateSiteVisitRequest;
use Illuminate\Http\Response;

class SitevisitRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_frontoffice || auth()->user()->is_client;
    }
    public function rules()
    {

        return [
            'follow_up_date' => 'required|date',
            'follow_up_time' => 'required|date_format:H:i',
            'deleted_at' => 'date',
            'lead_id' => [
                'required',
                'integer',
            ],
            'user_id' => [

            ],
            'notes' => 'nullable|string'
        ];
    }
}
