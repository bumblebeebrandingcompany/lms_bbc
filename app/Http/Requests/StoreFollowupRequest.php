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
        $lead_id = request()->input('lead_id');

        $follow_up_date = request()->input('follow_up_date');
        $follow_up_time = request()->input('follow_up_time');
        $notes = request()->input('notes');
        $deleted_at = request()->input('deleted_at');

        return [
            'follow_up_date' => 'required|date',
            'follow_up_time' => 'required|date_format:H:i',
            'deleted_at' => 'date',
            'lead_id' => [
                'required',
                'integer',
            ],

            'notes' => 'nullable|string'
        ];
    }
}
