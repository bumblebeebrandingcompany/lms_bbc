<?php

namespace App\Http\Requests;

use App\Models\Agency;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateAgencyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin;
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required'
            ],
            'email' => [
                'unique:agencies,email,' . request()->route('agency')->id,
            ],
            'contact_number_1' => [
                'string',
                'nullable',
            ],
        ];
    }
}
