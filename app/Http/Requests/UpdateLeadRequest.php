<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
class UpdateLeadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $lead_id = request()->input('lead_id');
        $project_id = request()->input('project_id');
        return [
            'name' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                })->ignore($lead_id),
            ],
            'phone' => [
                'required',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                })->ignore($lead_id),
            ],
            'project_id' => [
                'required',
                'integer',
            ]
        ];
    }
}
