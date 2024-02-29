<?php

namespace App\Http\Requests;

use App\Models\Followup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreSubSourceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => [
                'required',
                'integer',
            ],
            'campaign_id' => [
                'required',
                'integer',
            ],
            'source_id' => [
                'required',
                'integer',
            ],
            'name' => [
                'string',
                'required',
            ],
        ];
    }
}
