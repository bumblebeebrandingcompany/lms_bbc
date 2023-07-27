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
            <input type="hidden" name="lead_id" value="{{$lead->id}}">
            <div class="form-group">
                <label for="email" class="required">
                    @lang('messages.email')
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') ?? $lead->email }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone" class="required">
                    @lang('messages.phone')
                </label>
                <input type="number" name="phone" id="phone" value="{{ old('phone') ?? $lead->phone }}" class="form-control" required>
            </div>
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
            <div class="form-group">
                <label class="required" for="source_id">{{ trans('messages.source') }}</label>
                <select class="form-control select2 {{ $errors->has('source_id') ? 'is-invalid' : '' }}" name="source_id" id="source_id" required>
                    
                </select>
                @if($errors->has('source_id'))
                    <span class="text-danger">{{ $errors->first('source_id') }}</span>
                @endif
            </div>
            <h4>
                {{ trans('cruds.lead.fields.lead_details') }}
            </h4>
            <div class="lead_details">
                @php
                    $index_count = 0;
                @endphp
                @if(empty($lead->lead_info))
                    @includeIf('admin.leads.partials.lead_detail', ['key' => '', 'value' => '', $index = 0])
                @else
                    @foreach($lead->lead_info as $key => $value)
                        @php
                            $index_count = $loop->index;
                        @endphp
                        @includeIf('admin.leads.partials.lead_detail', ['key' => $key, 'value' => $value, $index = $loop->index])
                    @endforeach
                @endif
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-outline-primary add_lead_detail"
                    data-total="{{$index_count}}">
                    @lang('messages.add_lead_detail')
                </button>
                <button class="btn btn-primary float-right" type="submit">
                    {{ trans('global.update') }}
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
                    $('#campaign_id').val("{{$lead->campaign_id}}").change();
                    getSource();
                }
            });
        }

        function getSource() {
            let data = {
                project_id: $('#project_id').val(),
                campaign_id: $('#campaign_id').val(),
            };
            $.ajax({
                method:"GET",
                url: "{{route('admin.get.sources')}}",
                data: data,
                dataType: "json",
                success: function(response) {
                    $('#source_id').select2('destroy').empty().select2({data: response});
                    $('#source_id').val("{{$lead->source_id}}").change();
                }
            });
        }

        $(document).on('change', '#project_id', function() {
            getCampaigns();
        });

        $(document).on('change', '#campaign_id', function() {
            getSource();
        });

        $(document).on('click', '.add_lead_detail', function() {
            let index = $(this).attr('data-total');
            $.ajax({
                method:"GET",
                url: "{{route('admin.lead.detail.html')}}",
                data: {
                    index: index
                },
                dataType: "html",
                success: function(response) {
                    $("div.lead_details").append(response);
                    $(".add_lead_detail").attr('data-total', +index + 1);
                }
            });
        });

        $(document).on('click', '.delete_lead_detail_row', function() {
            if(confirm('Do you want to remove?')) {
                $(this).closest('.row').remove();
            }
        });

        getCampaigns();
    });
</script>
@endsection