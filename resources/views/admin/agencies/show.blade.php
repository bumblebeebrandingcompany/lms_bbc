@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.agency.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.agencies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.agency.fields.id') }}
                        </th>
                        <td>
                            {{ $agency->id }}
                        </td>
                    </tr>
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
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.agencies.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#agency_users" role="tab" data-toggle="tab">
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
        <div class="tab-pane" role="tabpanel" id="agency_users">
            @includeIf('admin.agencies.relationships.agencyUsers', ['users' => $agency->agencyUsers])
        </div>
        <div class="tab-pane" role="tabpanel" id="agency_campaigns">
            @includeIf('admin.agencies.relationships.agencyCampaigns', ['campaigns' => $agency->agencyCampaigns])
        </div>
    </div>
</div>

@endsection