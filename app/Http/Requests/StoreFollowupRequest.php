<?php

namespace App\Http\Requests;

use App\Models\Followup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreFollowupRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_admissionteam;
    }

    public function rules()
    {


        return [
            'followup_date' => 'required|date',
            'followup_time' => 'required|date_format:H:i',
            'deleted_at' => 'date',
            'lead_id' => [
                'required',
                'integer',
            ],

            'notes' => 'nullable|string'
        ];
    }
}
