<?php

namespace App\Http\Requests;

use App\Models\Agency;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyAgencyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:agencies,id',
        ];
    }
}
