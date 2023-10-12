<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div class="text-center">
            @php
                $avatar = 'https://ui-avatars.com/api/?background=random&font-size=0.7&name='.$lead->name;
            @endphp
            <img class="profile-user-img img-fluid img-circle" src="{{$avatar}}"
            alt="{{ $lead->name ?? '' }}">
        </div>
        <h3 class="profile-username text-center">
            {{ $lead->name ?? '' }}
        </h3>
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>{{ trans('messages.ref_num') }}</b> 
                <a class="float-right">{{ $lead->ref_num }}</a>
            </li>
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_lead_id')</b>
                <a class="float-right">
                    {!! $lead->sell_do_lead_id ?? '' !!}
                </a>
            </li> -->
            <li class="list-group-item">
                <b> @lang('messages.email')</b>
                <a class="float-right">
                    @if(auth()->user()->is_channel_partner_manager && !empty($lead->email))
                        {{ maskEmail($lead->email) }}
                    @else
                        {{ $lead->email ?? '' }}
                    @endif
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.additional_email_key')</b>
                <a class="float-right">
                    @if(auth()->user()->is_channel_partner_manager && !empty($lead->additional_email))
                        {{ maskEmail($lead->additional_email) }}
                    @else
                        {{ $lead->additional_email ?? '' }}
                    @endif
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.phone')</b>
                <a class="float-right">
                    @if(auth()->user()->is_channel_partner_manager && !empty($lead->phone))
                        {{ maskNumber($lead->phone) }}
                    @else
                        {{ $lead->phone ?? '' }}
                    @endif
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.secondary_phone_key')</b>
                <a class="float-right">
                    @if(auth()->user()->is_channel_partner_manager && !empty($lead->secondary_phone))
                        {{ maskNumber($lead->secondary_phone) }}
                    @else
                        {{ $lead->secondary_phone ?? '' }}
                    @endif
                </a>
            </li>
            <li class="list-group-item">
                <b>{{ trans('cruds.lead.fields.project') }}</b>
                <a class="float-right">
                    {{ $lead->project->name ?? '' }}
                </a>
            </li>
            <li class="list-group-item">
                <b>{{ trans('cruds.lead.fields.campaign') }}</b>
                <a class="float-right">
                    {{ $lead->campaign->campaign_name ?? '' }}
                </a>
            </li>
            <li class="list-group-item">
                <b>{{ trans('messages.source') }}</b>
                <a class="float-right">
                    {{ $lead->source->name ?? '' }}
                </a>
            </li>
            @php
                $lead_info = $lead->lead_info;
                if (
                    !empty($lead->source) && 
                    !empty($lead->source->name_key) && 
                    isset($lead_info[$lead->source->name_key]) &&
                    !empty($lead_info[$lead->source->name_key])
                ) {
                    unset($lead_info[$lead->source->name_key]);
                }

                if (
                    !empty($lead->source) && 
                    !empty($lead->source->email_key) && 
                    isset($lead_info[$lead->source->email_key]) &&
                    !empty($lead_info[$lead->source->email_key])
                ) {
                    unset($lead_info[$lead->source->email_key]);
                }

                if (
                    !empty($lead->source) && 
                    !empty($lead->source->phone_key) &&
                    isset($lead_info[$lead->source->phone_key]) &&
                    !empty($lead_info[$lead->source->phone_key])
                ) {
                    unset($lead_info[$lead->source->phone_key]);
                }

                if (
                    !empty($lead->source) && 
                    !empty($lead->source->additional_email_key) && 
                    isset($lead_info[$lead->source->additional_email_key]) &&
                    !empty($lead_info[$lead->source->additional_email_key])
                ) {
                    unset($lead_info[$lead->source->additional_email_key]);
                }

                if (
                    !empty($lead->source) && 
                    !empty($lead->source->secondary_phone_key) &&
                    isset($lead_info[$lead->source->secondary_phone_key]) &&
                    !empty($lead_info[$lead->source->secondary_phone_key])
                ) {
                    unset($lead_info[$lead->source->secondary_phone_key]);
                }
            @endphp
            @foreach($lead_info as $key => $value)
                <li class="list-group-item">
                    <b>{!! $key !!}</b>
                    <a class="float-right">
                        {!! $value !!}
                    </a>
                </li>
            @endforeach
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_created_date')</b>
                <a class="float-right">
                    @if(!empty($lead->sell_do_lead_created_at))
                        {{@format_datetime($lead->sell_do_lead_created_at)}}
                    @endif
                </a>
            </li> -->
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_status')</b>
                <a class="float-right">
                    {!! $lead->sell_do_status ?? '' !!}
                </a>
            </li> -->
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_stage')</b>
                <a class="float-right">
                    {!! $lead->sell_do_stage ?? '' !!}
                </a>
            </li> -->
            <li class="list-group-item">
                <b>@lang('messages.customer_comments')</b>
                <a class="float-right">
                    {!! $lead->comments ?? '' !!}
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.cp_comments')</b>
                <a class="float-right">
                    {!! $lead->cp_comments ?? '' !!}
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.added_by')</b>
                <a class="float-right">
                    {{ $lead->createdBy ? $lead->createdBy->name : ''}}
                </a>
            </li>
        </ul>
    </div>
</div>