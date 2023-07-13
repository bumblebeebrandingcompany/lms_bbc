@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.campaign.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.campaigns.update", [$campaign->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="campaign_name">{{ trans('cruds.campaign.fields.campaign_name') }}</label>
                <input class="form-control {{ $errors->has('campaign_name') ? 'is-invalid' : '' }}" type="text" name="campaign_name" id="campaign_name" value="{{ old('campaign_name', $campaign->campaign_name) }}" required>
                @if($errors->has('campaign_name'))
                    <span class="text-danger">{{ $errors->first('campaign_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.campaign_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="start_date">{{ trans('cruds.campaign.fields.start_date') }}</label>
                <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text" name="start_date" id="start_date" value="{{ old('start_date', $campaign->start_date) }}">
                @if($errors->has('start_date'))
                    <span class="text-danger">{{ $errors->first('start_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.start_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="end_date">{{ trans('cruds.campaign.fields.end_date') }}</label>
                <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text" name="end_date" id="end_date" value="{{ old('end_date', $campaign->end_date) }}">
                @if($errors->has('end_date'))
                    <span class="text-danger">{{ $errors->first('end_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.end_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.campaign.fields.source') }}</label>
                <select class="form-control {{ $errors->has('source') ? 'is-invalid' : '' }}" name="source" id="source" required>
                    <option value disabled {{ old('source', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\Campaign::SOURCE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('source', $campaign->source) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('source'))
                    <span class="text-danger">{{ $errors->first('source') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.source_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.campaign.fields.project') }}</label>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ (old('project_id') ? old('project_id') : $campaign->project->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('project'))
                    <span class="text-danger">{{ $errors->first('project') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="agency_id">{{ trans('cruds.campaign.fields.agency') }}</label>
                <select class="form-control select2 {{ $errors->has('agency') ? 'is-invalid' : '' }}" name="agency_id" id="agency_id">
                    @foreach($agencies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('agency_id') ? old('agency_id') : $campaign->agency->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('agency'))
                    <span class="text-danger">{{ $errors->first('agency') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.agency_helper') }}</span>
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