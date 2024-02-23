<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class WalkinStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_frontoffice;
    }

    public function rules()
    {
        $project_id = $this->input('project_id');

        return [
            'name' => 'required',
            'email' => [
                auth()->user()->is_superadmin ? '' : 'required',
                auth()->user()->is_superadmin ? '' : 'email',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->whereNotNull('email')->where('project_id', $project_id);
                }),
            ],
            'phone' => [
                auth()->user()->is_superadmin ? '' : 'required',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->whereNotNull('phone')->where('project_id', $project_id);
                }),
            ],
            'project_id' => 'required|integer',
            'parent_stage_id' => 'nullable|integer', // Add this line for parent_stage_id
        ];
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         $project_id = $this->input('project_id');
    //         $email = $this->input('email');
    //         $phone = $this->input('phone');

    //         $existingLead = Lead::where('project_id', $project_id)
    //             ->where(function ($query) use ($email, $phone) {
    //                 $query->where('email', $email)->orWhere('phone', $phone);
    //             })
    //             ->first();

    //         if ($existingLead) {
    //             $validator->errors()->add('lead_exists', 'Lead already exists.');
    //             // You may add more details of the existing lead to the message here
    //         }
    //     });
    // }
}
