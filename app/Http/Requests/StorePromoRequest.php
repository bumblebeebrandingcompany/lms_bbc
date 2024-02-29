<?php

namespace App\Http\Requests;

use App\Models\Followup;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePromoRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->is_superadmin || auth()->user()->is_admissionteam;
    }

    public function rules()
    {
        $name = request()->input('name');

        $project_id = request()->input('project_id');
        $campaign_id = request()->input('campaign_id');
        $source_id = request()->input('source_id');


        return [
            'name' => 'string',
            'project_id' => 'integer',
         'campaign_id=>integer',
         'source_id=>integer'
        ];
    }
}
