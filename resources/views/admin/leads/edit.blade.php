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
            <input type="hidden" name="lead_id" value="{{$lead->id}}" id="lead_id">
            <div class="form-group">
                <label for="name" class="required">
                    @lang('messages.name')
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') ?? $lead->name }}" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
            </div>
            <div class="form-group">
                <label for="email" @if(!auth()->user()->is_superadmin) class="required" @endif>
                    @lang('messages.email')
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') ?? $lead->email }}" class="form-control" @if(!auth()->user()->is_superadmin) required @endif>
            </div>
            <div class="form-group">
                <label for="additional_email_key">
                    @lang('messages.additional_email_key')
                </label>
                <input type="email" name="secondary_email" id="additional_email_key" value="{{ old('secondary_email') ?? $lead->secondary_email }}" class="form-control">
            </div>
            <input type="hidden" name="stage_id" value="{{ $lead->stage_id }}">

            <div class="form-group">
                <label for="phone" @if(!auth()->user()->is_superadmin) class="required" @endif>
                    @lang('messages.phone')
                </label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') ?? $lead->phone }}" class="form-control input_number" @if(!auth()->user()->is_superadmin) required @endif>
            </div>
            <div class="form-group">
                <label for="secondary_phone_key">
                    @lang('messages.secondary_phone_key')
                </label>
                <input type="text" name="secondary_phone" id="secondary_phone_key" value="{{ old('secondary_phone') ?? $lead->secondary_phone }}" class="form-control input_number">
            </div>
            {{-- <div class="form-group">
                <label class="required" for="project_id">{{ trans('cruds.lead.fields.project') }}</label>
                <br>
                <select class="form-control select2 {{ $errors->has('project') ? 'is-invalid' : '' }}" name="project_id" id="project_id" required>
                    @foreach($projects as $id => $entry)
                        <option value="{{ $id }}" {{ (old('project_id') ? old('project_id') : $lead->project->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                <br>
                @if($errors->has('project'))
                    <span class="text-danger">{{ $errors->first('project') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.lead.fields.project_helper') }}</span> --}}
            </div>
            @if(!auth()->user()->is_channel_partner)
                {{-- <div class="form-group">
                    <label for="campaign_id">{{ trans('cruds.lead.fields.campaign') }}</label>
                    <br>
                    <select class="form-control select2 {{ $errors->has('campaign') ? 'is-invalid' : '' }}" name="campaign_id" id="campaign_id">
                        @foreach($campaigns as $id => $entry)
                            <option value="{{ $id }}" {{ (old('campaign_id') ? old('campaign_id') : $lead->campaign->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    <br>
                    @if($errors->has('campaign'))
                        <span class="text-danger">{{ $errors->first('campaign') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.lead.fields.campaign_helper') }}</span>
                </div> --}}
                <div class="form-group">
                    <label class="required" for="source_id">{{ trans('messages.source') }}</label>
                    <br>
                    <select class="form-control select2 {{ $errors->has('source_id') ? 'is-invalid' : '' }}" name="source_id" id="source_id" required>

                    </select>
                    <br>
                    @if($errors->has('source_id'))
                        <span class="text-danger">{{ $errors->first('source_id') }}</span>
                    @endif
                </div>
            @endif
            <div class="form-group">
                <label for="comments">{{ trans('messages.comments') }}</label>
                <textarea name="comments" class="form-control" id="comments" rows="2">{!! old('comments') ?? $lead->comments !!}</textarea>
            </div>
            @if(auth()->user()->is_channel_partner)
                <div class="form-group">
                    <label for="cp_comments">{{ trans('messages.cp_comments') }}</label>
                    <textarea name="cp_comments" class="form-control" id="cp_comments" rows="2">{!! old('comments') ?? $lead->cp_comments !!}</textarea>
                </div>
            @endif
            @if(!auth()->user()->is_channel_partner)
                <h4>
                    {{ trans('cruds.lead.fields.lead_details') }}/@lang('messages.additional_fields')
                    <i class="fas fa-info-circle" data-html="true" data-toggle="tooltip" title="{{trans('messages.lead_details_help_text')}}"></i>
                </h4>
                <div class="lead_details">
                    @php
                        $index_count = 0;
                    @endphp
                    @if(empty($lead->lead_info))
                        @php
                            $index_count = -1;
                        @endphp
                        <!-- @includeIf('admin.leads.partials.lead_detail', ['key' => '', 'value' => '', $index = 0]) -->
                    @else
                        @foreach($lead->lead_info as $key => $value)
                            @php
                                $index_count = $loop->index;
                            @endphp
                            @includeIf('admin.leads.partials.lead_detail', ['key' => $key, 'value' => $value, $index = $loop->index])
                        @endforeach
                    @endif
                </div>
            @endif
            <div class="form-group">
                @if(!auth()->user()->is_channel_partner)
                    <input type="hidden" id="index_count" value="{{$index_count ?? -1}}">
                    <button type="button" class="btn btn-outline-primary add_lead_detail">
                        @lang('messages.add_lead_detail')
                    </button>
                    <button type="button" class="btn btn-outline-primary add_prefilled_lead_detail">
                        @lang('messages.add_prefilled_lead_detail')
                    </button>
                @endif
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
            getLeadDetailsRowHtml();
        });

        $(document).on('change', '#campaign_id', function() {
            getSource();
        });

        $(document).on('click', '.add_lead_detail', function() {
            let index = $("#index_count").val();
            $.ajax({
                method:"GET",
                url: "{{route('admin.lead.detail.html')}}",
                data: {
                    index: index
                },
                dataType: "html",
                success: function(response) {
                    $("div.lead_details").append(response);
                    $("#index_count").val(+index + 1);
                }
            });
        });

        $(document).on('click', '.delete_lead_detail_row', function() {
            if(confirm('Do you want to remove?')) {
                $(this).closest('.row').remove();
            }
        });

        function getLeadDetailsRowHtml() {
            $.ajax({
                method:"GET",
                url: "{{route('admin.lead.details.rows')}}",
                data: {
                    project_id: $('#project_id').val(),
                    lead_id: $('#lead_id').val()
                },
                dataType: "json",
                success: function(response) {
                    $("div.lead_details").html(response.html);
                    $("#index_count").val(response.count);
                }
            });
        }

        $(document).on('click', '.add_prefilled_lead_detail', function() {
            let index = $("#index_count").val();
            $.ajax({
                method:"GET",
                url: "{{route('admin.lead.detail.html')}}",
                data: {
                    index: index,
                    project_id: $('#project_id').val()
                },
                dataType: "html",
                success: function(response) {
                    $("div.lead_details").append(response);
                    $("#index_count").val(+index + 1);
                    $(".select-tags").select2();
                }
            });
        });

        getCampaigns();
    });
</script>
@endsection
