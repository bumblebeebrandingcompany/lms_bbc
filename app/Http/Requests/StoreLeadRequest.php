<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_channel_partner;
    }

    public function rules()
    {
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
                }),
            ],
            'phone' => [
                'required',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->where('project_id', $project_id);
                }),
            ],
            'project_id' => [
                'required',
                'integer',
            ]
        ];
    }
}
