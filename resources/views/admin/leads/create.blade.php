@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
                {{ trans('global.create') }} {{ trans('cruds.lead.title_singular') }}
            </h2>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.leads.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name" class="required">
Name                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" required>
                </div>
                <div class="form-group">
                    <label for="email" @if (!auth()->user()->is_superadmin) class="required" @endif>
                        @lang('messages.email')
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control"
                        @if (!auth()->user()->is_superadmin) required @endif>
                </div>
                <div class="form-group">
                    <label for="additional_email_key">
                        @lang('messages.additional_email_key')
                    </label>
                    <input type="email" name="additional_email" id="additional_email_key"
                        value="{{ old('additional_email') }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="phone" @if (!auth()->user()->is_superadmin) class="required" @endif>
                        @lang('messages.phone')
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="form-control input_number" @if (!auth()->user()->is_superadmin) required @endif>
                </div>
                <div class="form-group">
                    <label for="secondary_phone_key">
                        @lang('messages.secondary_phone_key')
                    </label>
                    <input type="text" name="secondary_phone" id="secondary_phone_key"
                        value="{{ old('secondary_phone') }}" class="form-control input_number">
                </div>
                <input type="hidden" name="stage_id" id="stage_id" value="8">

                <div class="form-group">
                    <label class="required" for="sub_source_id">SubSource</label>
                    <select class="form-control {{ $errors->has('sub_source_id') ? 'is-invalid' : '' }}" name="sub_source_id" id="sub_source_id" required>
                        <option value="">Select SubSource</option>
                        @foreach($promos as $subsource)
                            <option value="{{ $subsource->id }}" {{ old('sub_source_id') == $subsource->id ? 'selected' : '' }}>{{ $subsource->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('sub_source_id'))
                        <span class="text-danger">{{ $errors->first('sub_source_id') }}</span>
                    @endif
                    <span class="help-block">Select the subsource associated with the walk-in</span>
                </div>
                <div class="form-group">
                    <label for="comments">{{ trans('messages.customer_comments') }}</label>
                    <textarea name="comments" class="form-control" id="comments" rows="2">{!! old('comments') !!}</textarea>
                </div>
                @if (auth()->user()->is_channel_partner)
                    <div class="form-group">
                        <label for="cp_comments">{{ trans('messages.cp_comments') }}</label>
                        <textarea name="cp_comments" class="form-control" id="cp_comments" rows="2">{!! old('cp_comments') !!}</textarea>
                    </div>
                @endif
                @if (!auth()->user()->is_channel_partner)
                    <h4>
                        {{ trans('cruds.lead.fields.lead_details') }}/@lang('messages.additional_fields')
                        <i class="fas fa-info-circle" data-html="true" data-toggle="tooltip"
                            title="{{ trans('messages.lead_details_help_text') }}"></i>
                    </h4>
                    <div class="lead_details">
                        <!-- @includeIf('admin.leads.partials.lead_detail', [
                            'key' => '',
                            'value' => '',
                            ($index = 0),
                        ]) -->
                    </div>
                @endif
                <input type="hidden" id="index_count" value="-1">
                <div class="form-group">
                    @if (!auth()->user()->is_channel_partner)
                        <button type="button" class="btn btn-outline-primary add_lead_detail">
                            @lang('messages.add_lead_detail')
                        </button>
                        <button type="button" class="btn btn-outline-primary add_prefilled_lead_detail">
                            @lang('messages.add_prefilled_lead_detail')
                        </button>
                    @endif
                    <button class="btn btn-primary float-right" type="submit">
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
                    method: "GET",
                    url: "{{ route('admin.get.campaigns') }}",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        $('#campaign_id').select2('destroy').empty().select2({
                            data: response
                        });
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
                    method: "GET",
                    url: "{{ route('admin.get.sources') }}",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        $('#source_id').select2('destroy').empty().select2({
                            data: response
                        });
                    }
                });
            }

            $(document).on('change', '#project_id', function() {
                getCampaigns();
                let index = $("#index_count").val(-1);
                $("div.lead_details").html('');
                // getLeadDetailsRowHtml();
            });

            $(document).on('change', '#campaign_id', function() {
                getSource();
            });

            $(document).on('click', '.add_lead_detail', function() {
                let index = $("#index_count").val();
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.detail.html') }}",
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
                if (confirm('Do you want to remove?')) {
                    $(this).closest('.row').remove();
                }
            });

            // function getLeadDetailsRowHtml() {
            //     $.ajax({
            //         method:"GET",
            //         url: "{{ route('admin.lead.details.rows') }}",
            //         data: {
            //             project_id: $('#project_id').val()
            //         },
            //         dataType: "json",
            //         success: function(response) {
            //             $("div.lead_details").html(response.html);
            //             $("#index_count").val(response.count);
            //         }
            //     });
            // }

            $(document).on('click', '.add_prefilled_lead_detail', function() {
                let index = $("#index_count").val();
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.detail.html') }}",
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
        });
    </script>
@endsection
