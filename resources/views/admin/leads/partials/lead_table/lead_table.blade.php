<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Lead">
    <thead>
        <tr>
            <th width="10">

            </th>
            <th>
                @lang('messages.ref_num')
            </th>
            <th>
                @lang('messages.name')
            </th>
            <th>
                @lang('messages.email')
            </th>
            <th>
                @lang('messages.phone')
            </th>
            <th>
                @lang('messages.alternate_phone')
            </th>
            <!-- <th>
                @lang('messages.status')
            </th>
            <th>
                @lang('messages.sell_do_date')
            </th>
            <th>
                @lang('messages.sell_do_time')
            </th>
            <th>
                @lang('messages.sell_do_lead_id')
            </th> -->
            <th>
                {{ trans('cruds.lead.fields.project') }}
            </th>
            <th>
                {{ trans('cruds.lead.fields.campaign') }}
            </th>
            <th>
                {{ trans('messages.source') }}
            </th>
            <th>
                {{ trans('messages.added_by') }}
            </th>
            <th>
                {{ trans('messages.created_at') }}
            </th>
            <th>
                {{ trans('messages.updated_at') }}
            </th>
            <th>
                &nbsp;
            </th>
        </tr>
    </thead>
</table>