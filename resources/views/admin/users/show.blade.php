@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        </h2>
   </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a class="btn btn-default float-right" href="{{ route('admin.users.index') }}">
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
                                    {{ trans('messages.ref_num') }}
                                </th>
                                <td>
                                    {{ $user->ref_num }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.name') }}
                                </th>
                                <td>
                                    {{ $user->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('messages.representative_name') }}
                                </th>
                                <td>
                                    {{ $user->representative_name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.email') }}
                                </th>
                                <td>
                                    {{ $user->email }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.email_verified_at') }}
                                </th>
                                <td>
                                    {{ $user->email_verified_at }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.user_type') }}
                                </th>
                                <td>
                                    {{ App\Models\User::USER_TYPE_RADIO[$user->user_type] ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.address') }}
                                </th>
                                <td>
                                    {{ $user->address }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.contact_number_1') }}
                                </th>
                                <td>
                                    {{ $user->contact_number_1 }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.contact_number_2') }}
                                </th>
                                <td>
                                    {{ $user->contact_number_2 }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.website') }}
                                </th>
                                <td>
                                    {{ $user->website }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.client') }}
                                </th>
                                <td>
                                    {{ $user->client->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.user.fields.agency') }}
                                </th>
                                <td>
                                    {{ $user->agency->name ?? '' }}
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
                    <a class="nav-link active show" href="#created_by_projects" role="tab" data-toggle="tab">
                        {{ trans('cruds.project.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#client_projects" role="tab" data-toggle="tab">
                        {{ trans('cruds.project.title') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active show" role="tabpanel" id="created_by_projects">
                    @includeIf('admin.users.relationships.createdByProjects', ['projects' => $user->createdByProjects])
                </div>
                <div class="tab-pane" role="tabpanel" id="client_projects">
                    @includeIf('admin.users.relationships.clientProjects', ['projects' => $user->clientProjects])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection