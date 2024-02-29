@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
        {{ trans('global.create') }} {{ trans('cruds.campaign.title_singular') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route("admin.campaigns.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.campaign.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="start_date">{{ trans('cruds.campaign.fields.start_date') }}</label>
                <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text" name="start_date" id="start_date" value="{{ old('start_date') }}">
                @if($errors->has('start_date'))
                    <span class="text-danger">{{ $errors->first('start_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.start_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="end_date">{{ trans('cruds.campaign.fields.end_date') }}</label>
                <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text" name="end_date" id="end_date" value="{{ old('end_date') }}">
                @if($errors->has('end_date'))
                    <span class="text-danger">{{ $errors->first('end_date') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.end_date_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.campaign.fields.project') }}</label>
                <br>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ (old('project_id') == $id) || ($project_id == $id) ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                <br>
                @if($errors->has('project'))
                    <span class="text-danger">{{ $errors->first('project') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.campaign.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="agency_id">{{ trans('cruds.campaign.fields.agency') }}</label>
                <br>
                <select class="form-control select2 {{ $errors->has('agency') ? 'is-invalid' : '' }}" name="agency_id" id="agency_id">
                    @foreach($agencies as $id => $entry)
                        <option value="{{ $id }}" {{ old('agency_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                <br>
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
