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
        $promo_id = $this->input('promo_id');
        return [
            'name' => 'required',
            'email' => [
                auth()->user()->is_superadmin ? '' : 'required',
                auth()->user()->is_superadmin ? '' : 'email',
                Rule::unique('leads')->where(function ($query) use ($promo_id) {
                    return $query->whereNotNull('email')->where('promo_id', $promo_id);
                }),
            ],
            'phone' => [
                auth()->user()->is_superadmin ? '' : 'required',
                Rule::unique('leads')->where(function ($query) use ($promo_id) {
                    return $query->whereNotNull('phone')->where('promo_id', $promo_id);
                }),
            ],
            'promo_id' => 'required|integer',
            'stage_id' => 'nullable|integer', // Add this line for stage_id
        ];
    }

}
