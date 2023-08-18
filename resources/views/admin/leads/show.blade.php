@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
        {{ trans('global.show') }} {{ trans('cruds.lead.title') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-header">
        <a class="btn btn-default float-right" href="{{ route('admin.leads.index') }}">
        <i class="fas fa-chevron-left"></i>
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            @lang('messages.name')
                        </th>
                        <td>
                            {{ $lead->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.email')
                        </th>
                        <td>
                            @if(auth()->user()->is_channel_partner_manager && !empty($lead->email))
                                {{ maskEmail($lead->email) }}
                            @else
                                {{ $lead->email ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.additional_email_key')
                        </th>
                        <td>
                            @if(auth()->user()->is_channel_partner_manager && !empty($lead->additional_email))
                                {{ maskEmail($lead->additional_email) }}
                            @else
                                {{ $lead->additional_email ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.phone')
                        </th>
                        <td>
                            @if(auth()->user()->is_channel_partner_manager && !empty($lead->phone))
                                {{ maskNumber($lead->phone) }}
                            @else
                                {{ $lead->phone ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.secondary_phone_key')
                        </th>
                        <td>
                            @if(auth()->user()->is_channel_partner_manager && !empty($lead->secondary_phone))
                                {{ maskNumber($lead->secondary_phone) }}
                            @else
                                {{ $lead->secondary_phone ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.project') }}
                        </th>
                        <td>
                            {{ $lead->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lead.fields.campaign') }}
                        </th>
                        <td>
                            {{ $lead->campaign->campaign_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('messages.source') }}
                        </th>
                        <td>
                            {{ $lead->source->name ?? '' }}
                        </td>
                    </tr>
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
                        <tr>
                            <th>
                                {{$key}}
                            </th>
                            <td>
                                {{$value}}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>
                            @lang('messages.customer_comments')
                        </th>
                        <td>
                            {!! $lead->comments ?? '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.cp_comments')
                        </th>
                        <td>
                            {!! $lead->cp_comments ?? '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.added_by')
                        </th>
                        <td>
                            {{ $lead->createdBy ? $lead->createdBy->name : ''}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- webhook response -->
        @if(!empty($lead->webhook_response))
            <h3>Webhook responses</h3>
            <div class="form-group">
                <table class="table table-bordered table-striped">
                    <tbody>
                        @foreach($lead->webhook_response as $response)
                            <tr>
                                <th>
                                    {{$loop->iteration}}
                                </th>
                                <td>
                                    @if(is_string($response))
                                        {{$response}}
                                    @else
                                        <pre>
                                            {!! print_r($response, true) !!}
                                        </pre>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <!-- /webhook response -->
    </div>
</div>
@endsection