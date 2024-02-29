@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>
            {{ trans('cruds.campaign.title') }} <span class="text-primary">{{ $campaign->name }}</span>
        </h2>
   </div>
</div>
<div class="row">
    <div class="col-md-4">
<div class="card card-primary card-outline">
    <div class="card-header">
        <a class="btn btn-default float-right" href="{{ route('admin.campaigns.index') }}">
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
                            {{ trans('cruds.campaign.fields.name') }}
                        </th>
                        <td>
                            {{ $campaign->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.campaign.fields.start_date') }}
                        </th>
                        <td>
                            {{ $campaign->start_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.campaign.fields.end_date') }}
                        </th>
                        <td>
                            {{ $campaign->end_date }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.campaign.fields.project') }}
                        </th>
                        <td>
                            {{ $campaign->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.campaign.fields.agency') }}
                        </th>
                        <td>
                            {{ $campaign->agency->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.campaign.fields.created_at') }}
                        </th>
                        <td>
                            {{ $campaign->created_at }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.campaign.fields.updated_at') }}
                        </th>
                        <td>
                            {{ $campaign->updated_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<div class="col-md-8">
<div class="card card-primary card-outline">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link active show" href="#campaign_leads" role="tab" data-toggle="tab">
                {{ trans('cruds.lead.title') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#campaign_sources" role="tab" data-toggle="tab">
                {{ trans('cruds.source.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active show" role="tabpanel" id="campaign_leads">
            @includeIf('admin.campaigns.relationships.campaignLeads', ['leads' => $campaign->campaignLeads])
        </div>
        <div class="tab-pane" role="tabpanel" id="campaign_sources">
            @includeIf('admin.campaigns.relationships.campaignSources', ['sources' => $campaign->campaignSources])
        </div>
    </div>
</div>
</div>
</div>
@endsection
