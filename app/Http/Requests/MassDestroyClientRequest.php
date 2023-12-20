<?php

namespace App\Http\Requests;

use App\Models\Clients;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyClientRequest extends FormRequest
{
    public function authorize()
    {

        return auth()->user()->is_superadmin;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:clients,id',
        ];
    }
}
