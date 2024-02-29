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
    $rules = [
        'name' => 'required|string|max:255',
        'promo_id' => 'required|integer',
        'parent_stage_id' => 'nullable|integer',
    ];

    if (!auth()->user()->is_superadmin) {
        // If the user is not a superadmin, apply email and phone validation rules
        $rules['email'] = [
            'required',
            'email',
            Rule::unique('leads')->where(function ($query) {
                $promo = $this->promo; // Access promo relationship
                if ($promo) {
                    return $query->whereNotNull('email')->where('promo_id', $promo->project_id);
                }
                return $query; // No additional conditions if promo is null
            }),
        ];
        $rules['phone'] = [
            'required',
            Rule::unique('leads')->where(function ($query) {
                $promo = $this->promo; // Access promo relationship
                if ($promo) {
                    return $query->whereNotNull('phone')->where('promo_id', $promo->project_id);
                }
                return $query; // No additional conditions if promo is null
            }),
        ];
    }

    return $rules;
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
