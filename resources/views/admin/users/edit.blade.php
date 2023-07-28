@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <!-- <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <span class="text-danger">{{ $errors->first('roles') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div> -->
            <div class="form-group">
                <label class="required">{{ trans('cruds.user.fields.user_type') }}</label>
                @foreach(App\Models\User::USER_TYPE_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('user_type') ? 'is-invalid' : '' }}">
                        <input class="form-check-input user_type_input" type="radio" id="user_type_{{ $key }}" name="user_type" value="{{ $key }}" {{ old('user_type', $user->user_type) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="user_type_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('user_type'))
                    <span class="text-danger">{{ $errors->first('user_type') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.user_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.user.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address', $user->address) }}</textarea>
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contact_number_1">{{ trans('cruds.user.fields.contact_number_1') }}</label>
                <input class="form-control {{ $errors->has('contact_number_1') ? 'is-invalid' : '' }}" type="text" name="contact_number_1" id="contact_number_1" value="{{ old('contact_number_1', $user->contact_number_1) }}">
                @if($errors->has('contact_number_1'))
                    <span class="text-danger">{{ $errors->first('contact_number_1') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.contact_number_1_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contact_number_2">{{ trans('cruds.user.fields.contact_number_2') }}</label>
                <input class="form-control {{ $errors->has('contact_number_2') ? 'is-invalid' : '' }}" type="text" name="contact_number_2" id="contact_number_2" value="{{ old('contact_number_2', $user->contact_number_2) }}">
                @if($errors->has('contact_number_2'))
                    <span class="text-danger">{{ $errors->first('contact_number_2') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.contact_number_2_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="website">{{ trans('cruds.user.fields.website') }}</label>
                <input class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" type="text" name="website" id="website" value="{{ old('website', $user->website) }}">
                @if($errors->has('website'))
                    <span class="text-danger">{{ $errors->first('website') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.website_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="client_id">{{ trans('cruds.user.fields.client') }}</label>
                <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}" name="client_id" id="client_id">
                    @foreach($clients as $id => $entry)
                        <option value="{{ $id }}" {{ (old('client_id') ? old('client_id') : $user->client->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('client'))
                    <span class="text-danger">{{ $errors->first('client') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.client_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="agency_id">{{ trans('cruds.user.fields.agency') }}</label>
                <select class="form-control select2 {{ $errors->has('agency') ? 'is-invalid' : '' }}" name="agency_id" id="agency_id">
                    @foreach($agencies as $id => $entry)
                        <option value="{{ $id }}" {{ (old('agency_id') ? old('agency_id') : $user->agency->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('agency'))
                    <span class="text-danger">{{ $errors->first('agency') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.agency_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="sources">{{ trans('messages.sources') }}</label>
                <select class="form-control select2" name="sources[]" id="sources" multiple>
                    @foreach($sources as $id => $entry)
                        <option value="{{ $id }}"
                            @if(
                                !empty($user->sources) &&
                                in_array($id, $user->sources)
                            )
                                selected
                            @endif>
                            {{ $entry }}
                        </option>
                    @endforeach
                </select>
                <span class="help-block">{{ trans('messages.source_help_text') }}</span>
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
@section('scripts')
<script>
    @includeIf('admin.users.partials.user_crud_js')
</script>
@endsection