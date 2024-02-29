@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>
Edit SubSource            {{-- @if($source->is_cp_source)
                <i class="fas fa-info-circle" data-html="true"
                    data-toggle="tooltip"
                    title="{{trans('messages.source_used_for_channel_partner')}}">
                </i>
            @endif --}}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route("admin.subsource.update", [$subsource->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.source.fields.project') }}</label>
                <br>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}"
                    name="project_id" id="project_id" required>
                    @foreach ($projects as $id => $entry)
                        @if ($id == 44)
                            <option value="{{ $id }}" selected>{{ $entry }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('project'))
                    <span class="text-danger">{{ $errors->first('project') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.source.fields.project_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="campaign_id">{{ trans('cruds.source.fields.campaign') }}</label>
                <br>
                <select class="form-control select2 {{ $errors->has('campaign') ? 'is-invalid' : '' }}" name="campaign_id" id="campaign_id" required>
                    @foreach($campaigns as $id => $entry)
                        <option value="{{ $id }}" {{ (old('campaign_id') ? old('campaign_id') : $subsource->campaign->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('campaign'))
                    <span class="text-danger">{{ $errors->first('campaign') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.source.fields.campaign_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('messages.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ $subsource->source->name ?? old('name') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('messages.name_help_text') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">SubSource Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $subsource->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.source.fields.name_helper') }}</span>
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
    $(function() {
        function getCampaigns() {
            let data = {
                project_id: $('#project_id').val()
            };
            $.ajax({
                method:"GET",
                url: "{{route('admin.get.campaigns')}}",
                data: data,
                dataType: "json",
                success: function(response) {
                    $('#campaign_id').select2('destroy').empty().select2({data: response});
                    $('#campaign_id').val("{{$subsource->campaign_id}}").change();
                }
            });
        }

        $(document).on('change', '#project_id', function() {
            getCampaigns();
        });

        getCampaigns();
    });
</script>
@endsection
