@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
        {{ trans('global.edit') }} {{ trans('cruds.agency.title_singular') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route("admin.agencies.update", [$agency->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.agency.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $agency->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.agency.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.agency.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $agency->email) }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.agency.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contact_number_1">{{ trans('cruds.agency.fields.contact_number_1') }}</label>
                <input class="form-control {{ $errors->has('contact_number_1') ? 'is-invalid' : '' }}" type="text" name="contact_number_1" id="contact_number_1" value="{{ old('contact_number_1', $agency->contact_number_1) }}">
                @if($errors->has('contact_number_1'))
                    <span class="text-danger">{{ $errors->first('contact_number_1') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.agency.fields.contact_number_1_helper') }}</span>
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