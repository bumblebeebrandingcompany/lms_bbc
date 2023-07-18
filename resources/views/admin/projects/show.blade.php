@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
        {{ trans('global.show') }} {{ trans('cruds.project.title') }}
        </h2>
   </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a class="btn btn-default float-right" href="{{ route('admin.projects.index') }}">
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
                                    {{ trans('cruds.project.fields.name') }}
                                </th>
                                <td>
                                    {{ $project->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.project.fields.start_date') }}
                                </th>
                                <td>
                                    {{ $project->start_date }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.project.fields.end_date') }}
                                </th>
                                <td>
                                    {{ $project->end_date }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.project.fields.created_by') }}
                                </th>
                                <td>
                                    {{ $project->created_by->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.project.fields.client') }}
                                </th>
                                <td>
                                    {{ $project->client->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.project.fields.location') }}
                                </th>
                                <td>
                                    {{ $project->location }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.project.fields.description') }}
                                </th>
                                <td>
                                    {!! $project->description !!}
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
                <h3 class="card-title">
                    {{ trans('global.relatedData') }}
                </h3>
            </div>
            <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                <li class="nav-item">
                    <a class="nav-link active show" href="#project_leads" role="tab" data-toggle="tab">
                        {{ trans('cruds.lead.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#project_campaigns" role="tab" data-toggle="tab">
                        {{ trans('cruds.campaign.title') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active show" role="tabpanel" id="project_leads">
                    @includeIf('admin.projects.relationships.projectLeads', ['leads' => $project->projectLeads])
                </div>
                <div class="tab-pane" role="tabpanel" id="project_campaigns">
                    @includeIf('admin.projects.relationships.projectCampaigns', ['campaigns' => $project->projectCampaigns])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection