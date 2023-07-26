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
                            @lang('messages.email')
                        </th>
                        <td>
                            {{ $lead->email ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            @lang('messages.phone')
                        </th>
                        <td>
                            {{ $lead->phone ?? '' }}
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
                            @lang('messages.added_by')
                        </th>
                        <td>
                            {{ $lead->createdBy ? $lead->createdBy->name : ''}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection