<?php

namespace App\Http\Requests;

use App\Models\Agency;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAgencyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('agency_create');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
                'unique:agencies',
            ],
            'email' => [
                'required',
                'unique:agencies',
            ],
            'contact_number_1' => [
                'string',
                'nullable',
            ],
        ];
    }
}
