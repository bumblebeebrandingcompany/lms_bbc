@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
        {{ trans('global.show') }} {{ trans('cruds.agency.title') }}
        </h2>
   </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a class="btn btn-default float-right" href="{{ route('admin.agencies.index') }}">
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
                                    {{ trans('cruds.agency.fields.name') }}
                                </th>
                                <td>
                                    {{ $agency->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.agency.fields.email') }}
                                </th>
                                <td>
                                    {{ $agency->email }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('cruds.agency.fields.contact_number_1') }}
                                </th>
                                <td>
                                    {{ $agency->contact_number_1 }}
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
                    <a class="nav-link active show" href="#agency_users" role="tab" data-toggle="tab">
                        {{ trans('cruds.user.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#agency_campaigns" role="tab" data-toggle="tab">
                        {{ trans('cruds.campaign.title') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active show" role="tabpanel" id="agency_users">
                    @includeIf('admin.agencies.relationships.agencyUsers', ['users' => $agency->agencyUsers])
                </div>
                <div class="tab-pane" role="tabpanel" id="agency_campaigns">
                    @includeIf('admin.agencies.relationships.agencyCampaigns', ['campaigns' => $agency->agencyCampaigns])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection