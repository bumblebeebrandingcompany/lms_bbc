@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-12">
            <h2>
                @lang('messages.configure_webhook_details')
                <small>
                    <strong>Source:</strong>
                    <span class="text-primary">{{ $source->name }}</span>
                    <strong>Project:</strong>
                    <span class="text-primary">{{ optional($source->project)->name }}</span>
                </small>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">
                                {{ trans('messages.receive_webhook') }}
                            </h3>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-default float-right" href="{{ route('admin.sources.index') }}">
                                <i class="fas fa-chevron-left"></i>
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group webhook_div">
                                    <label for="webhook_url">
                                        {{ trans('messages.webhook_url') }}
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="webhook_url"
                                            value="{{ route('webhook.processor', ['secret' => $source->webhook_secret]) }}"
                                            class="form-control cursor-pointer copy_link" readonly>
                                        <div class="input-group-append cursor-pointer copy_link">
                                            <span class="input-group-text">
                                                <i class="fas fa-copy"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 d-flex justify-content-between mb-2">
                                <h3>
                                    {{ trans('messages.most_recent_lead') }}
                                </h3>
                                <button type="button" class="btn btn-outline-primary btn-xs refresh_latest_lead">
                                    <i class="fas fa-sync"></i>
                                    {{ trans('messages.refresh') }}
                                </button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ trans('messages.key') }}</th>
                                                <th>{{ trans('messages.value') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($lead) && !empty($lead->lead_info))
                                                @php
                                                    $serial_num = 0;

                                                @endphp
                                                <tr>
                                                    <td>
                                                        {{ trans('messages.name') }}
                                                    </td>
                                                    <td>
                                                        {!! $lead->name ?? '' !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ trans('messages.email') }}
                                                    </td>
                                                    <td>
                                                        {!! $lead->email ?? '' !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @lang('messages.additional_email_key')
                                                    </td>
                                                    <td>
                                                        {{ $lead->secondary_email ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ trans('messages.phone') }}
                                                    </td>
                                                    <td>
                                                        {!! $lead->phone ?? '' !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @lang('messages.secondary_phone_key')
                                                    </td>
                                                    <td>
                                                        {{ $lead->secondary_phone ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        essential_fields
                                                    </td>
                                                    <td>
                                                        {{ $lead->essential_fields ?? '' }}
                                                    </td>
                                                </tr>
                                                @php
                                                    $lead_info = $lead->lead_info;
                                                    $existing_keys = array_keys($lead->lead_info);
                                                    if (!empty($lead->source) && !empty($lead->source->name_key) && isset($lead_info[$lead->source->name_key]) && !empty($lead_info[$lead->source->name_key])) {
                                                        unset($lead_info[$lead->source->name_key]);
                                                    }
                                                    if (!empty($lead->source) && !empty($lead->source->email_key) && isset($lead_info[$lead->source->email_key]) && !empty($lead_info[$lead->source->email_key])) {
                                                        unset($lead_info[$lead->source->email_key]);
                                                    }

                                                    if (!empty($lead->source) && !empty($lead->source->phone_key) && isset($lead_info[$lead->source->phone_key]) && !empty($lead_info[$lead->source->phone_key])) {
                                                        unset($lead_info[$lead->source->phone_key]);
                                                    }

                                                    if (!empty($lead->source) && !empty($lead->source->additional_email_key) && isset($lead_info[$lead->source->additional_email_key]) && !empty($lead_info[$lead->source->additional_email_key])) {
                                                        unset($lead_info[$lead->source->additional_email_key]);
                                                    }

                                                    if (!empty($lead->source) && !empty($lead->source->secondary_phone_key) && isset($lead_info[$lead->source->secondary_phone_key]) && !empty($lead_info[$lead->source->secondary_phone_key])) {
                                                        unset($lead_info[$lead->source->secondary_phone_key]);
                                                    }
                                                @endphp
                                                @foreach ($lead_info as $key => $value)
                                                    @php
                                                        $serial_num = $loop->iteration;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            {!! $key !!}
                                                        </td>
                                                        <td>
                                                            {!! $value !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>
                                                        {{ trans('messages.created_at') }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($lead->created_at)->diffForHumans() }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        {{ trans('messages.updated_at') }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($lead->updated_at)->diffForHumans() }}
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="2" class="text-center">
                                                        <span class="text-center">
                                                            {{ trans('messages.no_data_found') }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- essential fields --}}
                        <div class="row">
                            <div class="col-md-12">
                                @if ($source->project->essential_fields)
                                    @foreach ($source->project->essential_fields as $key => $essentialField)
                                        @if (isset($essentialField['enabled']) && $essentialField['enabled'] === '1')
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="essential_fields">{{ $essentialField['name_data'] }}</label>
                                                        <input type="hidden" name="sales_fields[{{ $key }}][name_data]"
                                                            value="{{ $essentialField['name_data'] }}">
                                                        <span class="help-block">{{ $essentialField['name_value'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <input type="text" class="lead-input" name="essential_fields[{{ $key }}][value]"
                                                            value="{{ isset($essentialField['value']) ? $essentialField['value'] : '' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                @endif
                            </div>
                        </div>


                        {{-- Custom Fields --}}
                        <div class="row">
                            <div class="col-md-12">
                                @if ($source->project->custom_fields)
                                    @foreach ($source->project->custom_fields as $key => $customField)
                                        @if (isset($customField['enabled']) && $customField['enabled'] === '1')
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="custom_fields">{{ $customField['name_data'] }}</label>
                                                        <input type="hidden"
                                                            name="sales_fields[{{ $key }}][name_data]"
                                                            value="{{ $customField['name_data'] }}">
                                                        <br>
                                                        <span class="help-block">{{ $customField['name_value'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <br>
                                                        <select class="form-control select2" required>
                                                            <option value="">@lang('messages.please_select')</option>
                                                            @php
                                                                $existing_keys = optional($source->project)->webhook_fields ?? [];
                                                            @endphp
                                                            @foreach ($existing_keys as $existingKey)
                                                                <option value="{{ $existingKey }}">
                                                                    {{ $existingKey }}
                                                                    @if (!empty($existing_keys) && in_array($existingKey, $existing_keys))
                                                                        (@lang('messages.exist_in_recent_lead'))
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="lead-input"
                                                            name="sales_fields[{{ $key }}][value]" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                @endif
                            </div>
                        </div>


                        {{-- sales fields --}}
                        <div class="row">
                            <div class="col-md-12">
                                @if ($source->project->sales_fields)
                                    @foreach ($source->project->sales_fields as $key => $salesField)
                                        @if (isset($salesField['enabled']) && $salesField['enabled'] === '1')
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="sales_fields">{{ $salesField['name_data'] }}</label>
                                                        <input type="hidden"
                                                            name="sales_fields[{{ $key }}][name_data]"
                                                            value="{{ $salesField['name_data'] }}">
                                                        <span class="help-block">{{ $salesField['name_value'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <select class="form-control select2" required>
                                                            <option value="">@lang('messages.please_select')</option>
                                                            @php
                                                                $existing_keys = optional($source->project)->webhook_fields ?? [];
                                                            @endphp
                                                            @foreach ($existing_keys as $existingKey)
                                                                <option value="{{ $existingKey }}">
                                                                    {{ $existingKey }}
                                                                    @if (!empty($existing_keys) && in_array($existingKey, $existing_keys))
                                                                        (@lang('messages.exist_in_recent_lead'))
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="lead-input"
                                                            name="sales_fields[{{ $key }}][value]" readonly>
                                                    </div>
                                                </div>
                                                {{-- <select class="form-control select2" required>
                                                    <option value="">@lang('messages.please_select')</option>
                                                    @php
                                                        $existing_keys = optional($source->project)->webhook_fields ?? [];
                                                    @endphp
                                                    @foreach ($existing_keys as $existingKey)
                                                        <option value="{{ $existingKey }}">
                                                            {{ $existingKey }}
                                                            @if (!empty($existing_keys) && in_array($existingKey, $existing_keys))
                                                                (@lang('messages.exist_in_recent_lead'))
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select> --}}


                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="form-group">
                                        <input type="text" name="sales_fields[description]" value=""
                                            class="form-control">
                                        <div class="col-md-1 mt-auto mb-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm float-right delete_request_body_row">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>


                        {{-- system fields --}}
                        <div class="row">
                            <div class="col-md-12">
                                @if ($source->project->system_fields)
                                    @foreach ($source->project->system_fields as $key => $systemField)
                                        @if (isset($systemField['enabled']) && $systemField['enabled'] === '1')
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="system_fields">{{ $systemField['name_data'] }}</label>
                                                        <input type="hidden"
                                                            name="system_fields[{{ $key }}][name_data]"
                                                            value="{{ $systemField['name_data'] }}">
                                                        <br>
                                                        <span class="help-block">{{ $systemField['name_value'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <select class="form-control select2" required>
                                                            <option value="">@lang('messages.please_select')</option>
                                                            @php
                                                                $tags = optional($source->project)->webhook_fields ?? [];
                                                            @endphp
                                                            @foreach ($existing_keys as $existingKey)
                                                                <option value="{{ $existingKey }}">
                                                                    {{ $existingKey }}
                                                                    @if (!empty($existing_keys) && in_array($existingKey, $existing_keys))
                                                                        (@lang('messages.exist_in_recent_lead'))
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" class="lead-input"
                                                            name="sales_fields[{{ $key }}][value]" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="form-group">
                                        <input type="text" name="system_fields[description]" value=""
                                            class="form-control">
                                        <div class="col-md-1 mt-auto mb-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm float-right delete_request_body_row">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        var leadData = {!! json_encode($lead) !!};


        $('.select2').change(function() {
            const selectedKey = $(this).val();
            const inputField = $(this).closest('.row').find('.lead-input');

            // Retrieve the corresponding value from the leadData object
            const selectedValue = leadData[selectedKey];


            // Set the input field's value to the selected value (or handle "not found" case)
            if (selectedValue !== undefined) {
                inputField.val(selectedValue);
            } else {
                inputField.val('Value not found');
            }
        });
    </script>
    <script>
        $(function() {
            $(document).on('click', '.copy_link', function() {
                copyToClipboard($("#webhook_url").val());
            });

            function copyToClipboard(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                const span = document.createElement('span');
                span.innerText = 'Link copied to clipboard!';
                $(".webhook_div").append(span);
                setTimeout(() => {
                    span.remove();
                }, 300);
            }

            $(document).on('click', '.refresh_latest_lead', function() {
                location.reload();
            });
        });
    </script>
@endsection
