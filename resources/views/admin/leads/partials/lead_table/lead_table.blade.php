<table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Lead">
    <thead>
        <tr>
            <th width="10">

            </th>
            <th>
                @lang('messages.ref_num')
            </th>
            <th>
                Parent Name </th>
            <th>
                @lang('messages.email')
            </th>
            <th>
                Addtl Email
            </th>
            <th>
                @lang('messages.phone')
            </th>
            <th>
                @lang('messages.alternate_phone')
            </th>
            <th>
                Child Name
            </th>
            <th>
                Child Grade
            </th>
            <th>
                Stage
            </th>
            <th>
                Application No
            </th>
            <th>
                Supervised By
            </th>
            <th>
                Admission Team Name
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

            {{-- @if (!empty($leads) && !empty($leads[0]['essential_fields']))
            @foreach (json_decode($leads[0]['essential_fields'], true) as $key => $value)
                <th>{{ $key }}</th>
            @endforeach
        @endif --}}
            <th>
                &nbsp;
            </th>
        </tr>
    </thead>
</table>
