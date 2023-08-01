<?php

namespace App\Http\Requests;

use App\Models\Client;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreClientRequest extends FormRequest
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
                'unique:clients',
            ],
            'website' => [
                'string',
                'nullable',
            ],
            'contact_number_1' => [
                'string',
                'nullable',
            ],
            'contact_number_2' => [
                'string',
                'nullable',
            ],
        ];
    }
}
