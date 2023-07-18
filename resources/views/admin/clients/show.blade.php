@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.show') }} {{ trans('cruds.client.title') }}
        </h2>
   </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a class="btn btn-default float-right" href="{{ route('admin.clients.index') }}">
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
                                    {{ trans('cruds.client.fields.name') }}
                                </th>
                                <td>
                                    {{ $client->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.client.fields.email') }}
                                </th>
                                <td>
                                    {{ $client->email }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.client.fields.website') }}
                                </th>
                                <td>
                                    {{ $client->website }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.client.fields.contact_number_1') }}
                                </th>
                                <td>
                                    {{ $client->contact_number_1 }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.client.fields.contact_number_2') }}
                                </th>
                                <td>
                                    {{ $client->contact_number_2 }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.client.fields.other_details') }}
                                </th>
                                <td>
                                    {!! $client->other_details !!}
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
                    <a class="nav-link active show" href="#client_projects" role="tab" data-toggle="tab">
                        {{ trans('cruds.project.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#client_users" role="tab" data-toggle="tab">
                        {{ trans('cruds.user.title') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active show" role="tabpanel" id="client_projects">
                    @includeIf('admin.clients.relationships.clientProjects', ['projects' => $client->clientProjects])
                </div>
                <div class="tab-pane" role="tabpanel" id="client_users">
                    @includeIf('admin.clients.relationships.clientUsers', ['users' => $client->clientUsers])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection