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
                            {{ trans('cruds.lead.fields.lead_details') }}
                        </th>
                        <td>
                            @foreach($lead->lead_info as $key => $value)
                                {{$key}} : {{$value}} <br>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection