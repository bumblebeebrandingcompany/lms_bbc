<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class SystemCalendarController extends Controller
{
    public $sources = [
        [
            'model'      => '\App\Models\Lead',
            'date_field' => 'created_at',
            'field'      => 'lead_details',
            'prefix'     => 'New Lead -',
            'suffix'     => '',
            'route'      => 'admin.leads.edit',
        ],
        [
            'model'      => '\App\Models\Campaign',
            'date_field' => 'end_date',
            'field'      => 'campaign_name',
            'prefix'     => 'Campaign Ends -',
            'suffix'     => '',
            'route'      => 'admin.campaigns.edit',
        ],
        [
            'model'      => '\App\Models\Campaign',
            'date_field' => 'start_date',
            'field'      => 'campaign_name',
            'prefix'     => 'Campaign Starts -',
            'suffix'     => '',
            'route'      => 'admin.campaigns.edit',
        ],
    ];

    public function index()
    {
        $events = [];
        foreach ($this->sources as $source) {
            foreach ($source['model']::all() as $model) {
                $crudFieldValue = $model->getAttributes()[$source['date_field']];

                if (! $crudFieldValue) {
                    continue;
                }

                $events[] = [
                    'title' => trim($source['prefix'] . ' ' . $model->{$source['field']} . ' ' . $source['suffix']),
                    'start' => $crudFieldValue,
                    'url'   => route($source['route'], $model->id),
                ];
            }
        }

        return view('admin.calendar.calendar', compact('events'));
    }
}
