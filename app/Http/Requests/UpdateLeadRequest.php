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
    $user = auth()->user();
    return $user->is_superadmin || $user->is_client || $user->is_agency || $user->is_admissionteam || $user->is_frontoffice || $user->is_presales;
}
    public function rules()
    {
        $lead_id = request()->input('lead_id');
        $project_id = request()->input('project_id');
        return [
            'name' => [
                // 'required'
            ],
            'email' => [
                auth()->user()->is_superadmin ? '' : 'nullable',
                auth()->user()->is_superadmin ? '' : 'email',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->whereNotNull('email')->where('project_id', $project_id);
                })->ignore($lead_id),
            ],
            'phone' => [
                auth()->user()->is_superadmin ? '' : '=nullable',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->whereNotNull('phone')->where('project_id', $project_id);
                })->ignore($lead_id),
            ],
            'project_id' => [
                // 'required',
                'integer',
            ],
            'parent_stage_id'=>['integer'],
        ];
    }
}
