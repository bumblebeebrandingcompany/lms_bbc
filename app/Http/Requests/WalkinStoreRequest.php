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
        $sub_source_id = $this->input('sub_source_id');
        return [
            'name' => 'required',
            'email' => [
                auth()->user()->is_superadmin ? '' : 'required',
                auth()->user()->is_superadmin ? '' : 'email',
                Rule::unique('leads')->where(function ($query) use ($sub_source_id) {
                    return $query->whereNotNull('email')->where('sub_source_id', $sub_source_id);
                }),
            ],
            'phone' => [
                auth()->user()->is_superadmin ? '' : 'required',
                Rule::unique('leads')->where(function ($query) use ($sub_source_id) {
                    return $query->whereNotNull('phone')->where('sub_source_id', $sub_source_id);
                }),
            ],
            'sub_source_id' => 'required|integer',
            'stage_id' => 'nullable|integer', // Add this line for stage_id
        ];
    }

}
