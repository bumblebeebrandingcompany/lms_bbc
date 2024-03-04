<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use App\Utils\Util;
use Illuminate\Http\Request;

class ReportController
{
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
        if(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager) {
            abort(403, 'Unauthorized.');
        }

        $__global_clients_filter = $this->util->getGlobalClientsFilter();
        if(!empty($__global_clients_filter)) {
            $project_ids = $this->util->getClientsProjects($__global_clients_filter);
            $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
        } else {
            $project_ids = $this->util->getUserProjects(auth()->user());
            $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        }

        $settings1 = [
            'chart_title'           => 'Application',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Application',
            'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings1['total_number'] = 0;
        if (class_exists($settings1['model'])) {
            $settings1['total_number'] = $settings1['model']::when(isset($settings1['filter_field']), function ($query) use ($settings1) {
                if (isset($settings1['filter_days'])) {
                    return $query->where($settings1['filter_field'], '>=',
                        now()->subDays($settings1['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings1['filter_period'])) {
                    switch ($settings1['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month': $start = date('Y-m') . '-01';
                            break;
                        case 'year': $start = date('Y') . '-01-01';
                            break;
                    }
                    if (isset($start)) {
                        return $query->where($settings1['filter_field'], '>=', $start);
                    }
                }
            })
            ->{$settings1['aggregate_function'] ?? 'count'}($settings1['aggregate_field'] ?? '*');
        }

          $settings2  = [
            'chart_title'           => 'Admission',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Admission',
            'group_by_field'        => 'created_at', // Assuming 'created_at' is the timestamp field
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings2['total_number'] = 0;
        if (class_exists($settings2['model'])) {
            $settings2['total_number'] = $settings2['model']::when(isset($settings1['filter_field']), function ($query) use ($settings2) {
                if (isset($settings2['filter_days'])) {
                    return $query->where($settings2['filter_field'], '>=',
                        now()->subDays($settings2['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings2['filter_period'])) {
                    switch ($settings2['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month': $start = date('Y-m') . '-01';
                            break;
                        case 'year': $start = date('Y') . '-01-01';
                            break;
                    }
                    if (isset($start)) {
                        return $query->where($settings2['filter_field'], '>=', $start);
                    }
                }
            })
            ->{$settings2['aggregate_function'] ?? 'count'}($settings2['aggregate_field'] ?? '*');
        }
        $settings3 = [
            'chart_title'           => 'Walkin',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Walkin',
            'group_by_field'        => 'email_verified_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'user',
        ];

        $settings3['total_number'] = 0;
        if (class_exists($settings3['model'])) {
            $settings3['total_number'] = $settings3['model']::when(isset($settings3['filter_field']), function ($query) use ($settings3) {
                if (isset($settings3['filter_days'])) {
                    return $query->where($settings3['filter_field'], '>=',
                        now()->subDays($settings3['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings3['filter_period'])) {
                    switch ($settings3['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings3['filter_field'], '>=', $start);
                    }
                }
            })
            ->{$settings3['aggregate_function'] ?? 'count'}($settings3['aggregate_field'] ?? '*');
        }

        $settings4 = [
            'chart_title'           => 'Withdrawn',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'client',
        ];

        $settings4['total_number'] = 0;
        if (class_exists($settings4['model'])) {
            $settings4['total_number'] = $settings4['model']::when(isset($settings4['filter_field']), function ($query) use ($settings4) {
                if (isset($settings4['filter_days'])) {
                    return $query->where($settings4['filter_field'], '>=',
                        now()->subDays($settings4['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings4['filter_period'])) {
                    switch ($settings4['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings4['filter_field'], '>=', $start);
                    }
                }
            })->where('stage_id',29)
                ->{$settings4['aggregate_function'] ?? 'count'}($settings4['aggregate_field'] ?? '*');
        }
        $settings5 = [
            'chart_title'           => 'BBC leads',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Lead',
            'group_by_field'        => 'created_at',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y H:i:s',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
            'translation_key'       => 'agency',
        ];
        $settings5['total_number'] = 0;
        if (class_exists($settings5['model'])) {
            $settings5['total_number'] = $settings5['model']::when(isset($settings5['filter_field']), function ($query) use ($settings5) {
                if (isset($settings5['filter_days'])) {
                    return $query->where($settings5['filter_field'], '>=',
                        now()->subDays($settings5['filter_days'])->format('Y-m-d'));
                } elseif (isset($settings5['filter_period'])) {
                    switch ($settings5['filter_period']) {
                        case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                        break;
                        case 'month': $start = date('Y-m') . '-01';
                        break;
                        case 'year': $start = date('Y') . '-01-01';
                        break;
                    }
                    if (isset($start)) {
                        return $query->where($settings5['filter_field'], '>=', $start);
                    }
                }

            })->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
      ->where('sub_source.campaign_id', 1) // Assuming 'campaign_id' is the correct field name in 'subsource' table
      ->{$settings5['aggregate_function'] ?? 'count'}($settings5['aggregate_field'] ?? '*');
}
$settings6 = [
    'chart_title'           => 'Qmis Direct',
    'chart_type'            => 'number_block',
    'report_type'           => 'group_by_date',
    'model'                 => 'App\Models\Lead',
    'group_by_field'        => 'created_at',
    'group_by_period'       => 'day',
    'aggregate_function'    => 'count',
    'filter_field'          => 'created_at',
    'group_by_field_format' => 'd-m-Y H:i:s',
    'column_class'          => 'col-md-4',
    'entries_number'        => '5',
    'translation_key'       => 'agency',
];
$settings6['total_number'] = 0;
if (class_exists($settings6['model'])) {
    $settings6['total_number'] = $settings6['model']::when(isset($settings6['filter_field']), function ($query) use ($settings6) {
        if (isset($settings6['filter_days'])) {
            return $query->where($settings6['filter_field'], '>=',
                now()->subDays($settings6['filter_days'])->format('Y-m-d'));
        } elseif (isset($settings6['filter_period'])) {
            switch ($settings6['filter_period']) {
                case 'week': $start = date('Y-m-d', strtotime('last Monday'));
                break;
                case 'month': $start = date('Y-m') . '-01';
                break;
                case 'year': $start = date('Y') . '-01-01';
                break;
            }
            if (isset($start)) {
                return $query->where($settings6['filter_field'], '>=', $start);
            }
        }

    })->join('sub_source', 'leads.sub_source_id', '=', 'sub_source.id') // Adjust the join condition based on your actual relationship
->where('sub_source.campaign_id', 2) // Assuming 'campaign_id' is the correct field name in 'subsource' table
->{$settings6['aggregate_function'] ?? 'count'}($settings6['aggregate_field'] ?? '*');
}
        return view('admin.report.index', compact('settings1', 'settings2', 'settings3', 'settings4','settings5','settings6'));
    }
    public function storeGlobalClientFilters(Request $request)
    {
        if($request->ajax()) {
            $client_ids = $request->input('client_ids');
            session(['__global_clients_filter' => $client_ids]);
            return [
                'success' => true,
                'msg' => __('messages.success')
            ];
        }

    }
}
