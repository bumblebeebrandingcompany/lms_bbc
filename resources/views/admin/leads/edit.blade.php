@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
        {{ trans('global.edit') }} {{ trans('cruds.lead.title_singular') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route("admin.leads.update", [$lead->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.lead.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ (old('project_id') ? old('project_id') : $lead->project->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('project'))
                    <span class="text-danger">{{ $errors->first('project') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="campaign_id">{{ trans('cruds.lead.fields.campaign') }}</label>
                <select class="form-control select2 {{ $errors->has('campaign') ? 'is-invalid' : '' }}" name="campaign_id" id="campaign_id">
                    @foreach($campaigns as $id => $entry)
                        <option value="{{ $id }}" {{ (old('campaign_id') ? old('campaign_id') : $lead->campaign->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('campaign'))
                    <span class="text-danger">{{ $errors->first('campaign') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.campaign_helper') }}</span>
            </div>
            @if(empty($lead->lead_info))
                <div class="form-group">
                    <label class="required" for="lead_details">{{ trans('cruds.lead.fields.lead_details') }}</label>
                    <textarea class="form-control {{ $errors->has('lead_details') ? 'is-invalid' : '' }}" name="lead_details" id="lead_details" required>{{ old('lead_details', json_encode($lead->lead_info)) }}</textarea>
                    @if($errors->has('lead_details'))
                        <span class="text-danger">{{ $errors->first('lead_details') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.lead.fields.lead_details_helper') }}</span>
                </div>
            @else
                <h4>
                    {{ trans('cruds.lead.fields.lead_details') }}
                </h4>
                <div class="row">
                    @foreach($lead->lead_info as $key => $value)
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>
                                    {{ $key }}
                                </label>
                                <input type="text" name="lead_details[{{$key}}]" value="{{$value}}" class="form-control">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                    {{ trans('global.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection