<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;
use App\Utils\Util;
class LeadsExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    protected $request;
    protected $util;
    protected $additional_columns;
    public function __construct($request)
    {
        $this->request = $request;
        $this->additional_columns = !empty($request->get('additional_columns')) ? explode(',',$request->get('additional_columns')) : [];
        $this->util = new Util();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->util->getFIlteredLeads($this->request)->get();
    }

    public function map($row): array
    {
        $row_values =  [
            $row->ref_num,
            $row->name,
            $row->email,
            $row->additional_email,
            $row->phone,
            $row->secondary_phone,
            $row->sell_do_is_exist ? 'Duplicate' : 'New',
            !empty($row->sell_do_lead_created_at) ? Carbon::parse($row->sell_do_lead_created_at)->format('d/m/Y') : '',
            !empty($row->sell_do_lead_created_at) ? Carbon::parse($row->sell_do_lead_created_at)->format('h:i A') : '',
            $row->sell_do_status,
            $row->sell_do_stage,
            $row->sell_do_lead_id,
            $row->project ? $row->project->name : '',
            $row->campaign ? $row->campaign->campaign_name : '',
            $row->source ? $row->source->name : '',
            $row->comments,
            $row->cp_comments,
            $row->createdBy ? $row->createdBy->name : '',
            Carbon::parse($row->created_at)->toDayDateTimeString(),
        ];

        if(
            isset($this->additional_columns) && 
            !empty($this->additional_columns) &&
            !empty($row->lead_info)
        ) {
            foreach ($this->additional_columns as $column) {
                $row_values[] = $row->lead_info[$column] ?? '';
            }
        }

        return $row_values;
    }

    public function headings(): array
    {
        $headings_arr = [
            __('messages.ref_num'),
            __('messages.name'),
            __('messages.email'),
            __('messages.additional_email_key'),
            __('messages.phone'),
            __('messages.alternate_phone'),
            __('messages.status'),
            __('messages.sell_do_date'),
            __('messages.sell_do_time'),
            __('messages.sell_do_status'),
            __('messages.sell_do_stage'),
            __('messages.sell_do_lead_id'),
            __('cruds.lead.fields.project'),
            __('cruds.lead.fields.campaign'),
            __('messages.source'),
            __('messages.customer_comments'),
            __('messages.cp_comments'),
            __('messages.added_by'),
            __('messages.created_at')
        ];

        if(isset($this->additional_columns) && !empty($this->additional_columns)) {
            $headings_arr = array_merge($headings_arr, array_values($this->additional_columns));
        }

        return $headings_arr;
    }
}
