<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Utils\Util;
class SystemCalendarController extends Controller
{
    public $sources = [
        [
            'model'      => '\App\Models\Lead',
            'date_field' => 'created_at',
            'field'      => 'lead_details',
            'prefix'     => 'New Lead -',
            'suffix'     => '',
            'route'      => 'admin.leads.show',
        ],
        [
            'model'      => '\App\Models\Campaign',
            'date_field' => 'end_date',
            'field'      => 'campaign_name',
            'prefix'     => 'Campaign Ends -',
            'suffix'     => '',
            'route'      => 'admin.campaigns.show',
        ],
        [
            'model'      => '\App\Models\Campaign',
            'date_field' => 'start_date',
            'field'      => 'campaign_name',
            'prefix'     => 'Campaign Starts -',
            'suffix'     => '',
            'route'      => 'admin.campaigns.show',
        ],
    ];

    /**
    * All Utils instance.
    *
    */
    protected $util;

    /**
    * Constructor
    *
    */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index()
    {
        $events = [];
        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        foreach ($this->sources as $source) {
            if($source['model'] == '\App\Models\Lead') {
                $models = $source['model']::where(function ($q) use($project_ids, $campaign_ids) {
                                $q->whereIn('project_id', $project_ids)
                                    ->orWhereIn('campaign_id', $campaign_ids);
                            })->groupBy('id')->get();
            }

            if($source['model'] == '\App\Models\Campaign') {
                $models = $source['model']::whereIn('id', $campaign_ids)
                            ->get();
            }
            foreach ($models as $model) {
                $crudFieldValue = $model->getAttributes()[$source['date_field']];

                if (! $crudFieldValue) {
                    continue;
                }
                $title = is_array($model->{$source['field']}) ? http_build_query($model->{$source['field']}, '', ', ') : $model->{$source['field']};
                $events[] = [
                    'title' => trim($source['prefix'] . ' ' . $title . ' ' . $source['suffix']),
                    'start' => $crudFieldValue,
                    'url'   => route($source['route'], $model->id),
                ];
            }
        }

        return view('admin.calendar.calendar', compact('events'));
    }
}
