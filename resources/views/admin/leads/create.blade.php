@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.lead.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.leads.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.lead.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
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
                        <option value="{{ $id }}" {{ old('campaign_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('campaign'))
                    <span class="text-danger">{{ $errors->first('campaign') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.campaign_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="lead_details">{{ trans('cruds.lead.fields.lead_details') }}</label>
                <textarea class="form-control {{ $errors->has('lead_details') ? 'is-invalid' : '' }}" name="lead_details" id="lead_details" required>{{ old('lead_details') }}</textarea>
                @if($errors->has('lead_details'))
                    <span class="text-danger">{{ $errors->first('lead_details') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.lead_details_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection