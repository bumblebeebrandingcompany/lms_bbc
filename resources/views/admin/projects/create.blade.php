@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
                {{ trans('global.create') }} {{ trans('cruds.project.title_singular') }}
            </h2>
        </div>
    </div>
    <div class="card card-primary card-outline">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.project.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                        id="name" value="{{ old('name', '') }}" required>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.name_helper') }}</span>
                </div>
                {{-- <div class="form-group">
                    <label for="start_date">{{ trans('cruds.project.fields.start_date') }}</label>
                    <input class="form-control date {{ $errors->has('start_date') ? 'is-invalid' : '' }}" type="text"
                        name="start_date" id="start_date" value="{{ old('start_date') }}">
                    @if ($errors->has('start_date'))
                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.start_date_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="end_date">{{ trans('cruds.project.fields.end_date') }}</label>
                    <input class="form-control date {{ $errors->has('end_date') ? 'is-invalid' : '' }}" type="text"
                        name="end_date" id="end_date" value="{{ old('end_date') }}">
                    @if ($errors->has('end_date'))
                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.end_date_helper') }}</span>
                </div> --}}
                <div class="form-group">
                    <label class="required" for="client_id">{{ trans('cruds.project.fields.client') }}</label>
                    <br>
                    <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}" name="client_id"
                        id="client_id" required>
                        @foreach ($clients as $id => $entry)
                            <option value="{{ $id }}" {{ old('client_id') == $id ? 'selected' : '' }}>
                                {{ $entry }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('client'))
                        <span class="text-danger">{{ $errors->first('client') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.client_helper') }}</span>
                </div>
                {{-- <div class="form-group">
                    <label for="location">{{ trans('cruds.project.fields.location') }}</label>
                    <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text"
                        name="location" id="location" value="{{ old('location', '') }}">
                    @if ($errors->has('location'))
                        <span class="text-danger">{{ $errors->first('location') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.location_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="description">{{ trans('cruds.project.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description"
                        id="description">{!! old('description') !!}</textarea>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.project.fields.description_helper') }}</span>
                </div> --}}
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="essential_fields">{{ trans('cruds.project.fields.essential') }}</label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="essential_fields">Full Name Of the Lead</label>
                            <input type="hidden" name="essential_fields[0][name_data]" value="Full Name Of the Lead">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[0][name_key]" id="email_value"
                                value="name" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[0][name_value]"
                                id="name_value" value="bbc_lms[lead][name]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="essential_fields[0][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="essential_fields[0][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="essential_fields"></label>
                            <input type="hidden" name="essential_fields[1][name_data]" value="">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[1][name_key]"
                                id="email_value" value="phone_number" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[1][name_value]"
                                id="name_value" value="bbc_lms[lead][phone]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="essential_fields[1][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="essential_fields[1][disabled]" value="0">
                            </label>
                        </div>
                    </div>

                    <div class="row mb-2">

                        <div class="col-md-3">
                            <label for="essential_fields">Email Of the Lead</label>
                            <input type="hidden" name="essential_fields[2][name_data]" value="Email Of the Lead">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[2][name_key]"
                                id="email_value" value="email" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[2][name_value]"
                                id="name_value" value="bbc_lms[lead][email]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="essential_fields[2][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="essential_fields[2][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="essential_fields">Addl Number Of the Lead</label>
                            <input type="hidden" name="essential_fields[3][name_data]" value="Addl Number Of the Lead">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[3][name_key]"
                                id="email_value" value="addl_number" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[3][name_value]"
                                id="name_value" value="bbc_lms[lead][addl_number]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="essential_fields[3][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="essential_fields[3][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="essential_fields">Addl Email Of the Lead</label>
                            <input type="hidden" name="essential_fields[4][name_data]" value="Addl Email Of the Lead">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[4][name_key]"
                                id="email_value" value="addl_email" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="essential_fields[4][name_value]"
                                id="name_value" value="bbc_lms[lead][addl_email]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="essential_fields[4][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="essential_fields[4][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div id="essential-fields-container">
                    </div>
                </div>
                {{-- sales fields --}}
                <div class="form-group">

                    <div class="col-md-6">
                        <label for="sales_fields">{{ trans('cruds.project.fields.sales') }}</label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="sales_fields">Sales User</label>
                            <input type="hidden" name="sales_fields[0][name_data]" value="Sales User">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="sales_fields[0][name_key]" id="email_value"
                                value="sales_user" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="sales_fields[0][name_value]"
                                id="name_value" value="bbc_lms[lead][sales_user]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="sales_fields[0][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="sales_fields[0][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="sales_fields">Lead Pickup Date</label>
                            <input type="hidden" name="sales_fields[1][name_data]" value="Lead Pickup date">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="sales_fields[1][name_key]" id="email_value"
                                value="lead_pickup_date" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="sales_fields[1][name_value]"
                                id="name_value" value="bbc_lms[lead][lead_pickup_date]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="sales_fields[1][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="sales_fields[1][disabled]" value="0">
                            </label>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="sales_fields">Lead Pickup Time</label>
                            <input type="hidden" name="sales_fields[2][name_data]" value="Lead Pickup Time">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="sales_fields[2][name_key]" id="email_value"
                                value="lead_pickup_time" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="sales_fields[2][name_value]"
                                id="name_value" value="bbc_lms[lead][lead_pickup_time]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="sales_fields[2][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="sales_fields[2][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                </div>
                <div id="sales-fields-container">
                </div>


                {{-- system fields --}}
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="system_fields">{{ trans('cruds.project.fields.system') }}</label>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Project</label>
                            <input type="hidden" name="system_fields[0][name_data]" value="Project">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[0][name_key]"
                                id="email_value" value="project" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[0][name_value]"
                                id="name_value" value="bbc_lms[lead][project]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[0][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[0][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Campaign Name</label>
                            <input type="hidden" name="system_fields[1][name_data]" value="Campaign Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[1][name_key]"
                                id="email_value" value="name" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[1][name_value]"
                                id="name_value" value="bbc_lms[lead][name]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[1][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[1][disabled]" value="0">
                            </label>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Source Name</label>
                            <input type="hidden" name="system_fields[2][name_data]" value="Source Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[2][name_key]"
                                id="email_value" value="name" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[2][name_value]"
                                id="name_value" value="bbc_lms[lead][name]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[2][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[2][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">

                        <div class="col-md-3">
                            <label for="system_fields">Sub Source</label>
                            <input type="hidden" name="system_fields[3][name_data]" value="Sub Source">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[3][name_key]"
                                id="email_value" value="sub_source" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[3][name_value]"
                                id="name_value" value="bbc_lms[lead][sub_source]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[3][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[3][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Lead Date</label>
                            <input type="hidden" name="system_fields[4][name_data]" value="Lead Date">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[4][name_key]"
                                id="email_value" value="lead_date" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[4][name_value]"
                                id="name_value" value="bbc_lms[lead][lead_date]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[4][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[4][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Lead Time</label>
                            <input type="hidden" name="system_fields[5][name_data]" value="Lead Time">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[5][name_key]"
                                id="email_value" value="lead_time" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[5][name_value]"
                                id="name_value" value="bbc_lms[lead][lead_time]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[5][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[5][disabled]" value="0">
                            </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Form Name</label>
                            <input type="hidden" name="system_fields[6][name_data]" value="Form Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[6][name_key]"
                                id="email_value" value="form_name" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[6][name_value]"
                                id="name_value" value="bbc_lms[lead][form_name]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[6][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[6][disabled]" value="0">
                            </label>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label for="system_fields">Page Url</label>
                            <input type="hidden" name="system_fields[7][name_data]" value="Page Url">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[7][name_key]"
                                id="email_value" value="page_url" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="system_fields[7][name_value]"
                                id="name_value" value="bbc_lms[lead][page_url]" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="switch">
                                <input type="checkbox" name="system_fields[7][enabled]" value="1">
                                <span class="slider round"></span>
                                <input type="hidden" name="system_fields[7][disabled]" value="0">
                            </label>
                        </div>
                    </div>

                </div>
                <div id="system-fields-container">
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="custom_fields">{{ trans('cruds.project.fields.custom') }}</label>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" id="add-custom-field" class="btn btn-primary">Add Field</button>
                        </div>
                    </div>
                    <div id="custom-fields-container">
                    </div>
                </div>
                <div class="form-group text-end">
                    <input type="hidden" id="custom-fields-json" name="custom_fields_json" value="">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

{{-- essential fields --}}

@section('scripts')
    <script>
        $(document).ready(function() {
            let essentialFieldsArray = [];
            $('div.row.mb-2').each(function() {
                const nameData = $(this).find('input[name="name_data[]"]').val();
                const nameKey = $(this).find('input[name="name_key[]"]').val();
                const nameValue = $(this).find('input[name="name_value[]"]').val();
                const enabled = $(this).find('input[name="enabled[]"]').prop('checked') ? '1' : '0';
                const disabled = '0'; // Assuming 'disabled' is always '0' based on your format

                const fieldData = {
                    name_data: nameData,
                    name_key: nameKey,
                    name_value: nameValue,
                    enabled: enabled,
                    disabled: disabled,
                };

                essentialFieldsArray.push(fieldData);
            });

            $('#essential-fields-json').val(JSON.stringify(essentialFieldsArray));
        });
    </script>



    {{-- custom fields --}}
    <script>
        $(document).ready(function() {
            let fieldCounter =
                {{ isset($project) && is_array($project->custom_fields) ? count($project->custom_fields) : 0 }};
            const customFields = {};

            $('#add-custom-field').on('click', function() {
                fieldCounter++;

                const customFieldInput = `
            <div class="row mb-2">
                <div class="col-md-3">
                    <input class="form-control" type="text" name="custom_fields[${fieldCounter}][name_data]" placeholder="Field Name">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="custom_fields[${fieldCounter}][name_key]" placeholder="Field Name">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="custom_fields[${fieldCounter}][name_value]" placeholder="Field Value">
                </div>
                <div class="col-md-1">
                    <label class="switch">
                        <input type="checkbox" name="custom_fields[${fieldCounter}][enabled]" value="1">
                        <span class="slider round"></span>
                        <input type="hidden" name="custom_fields[${fieldCounter}][disabled]" value="0">
                    </label>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-field">Remove</button>
                </div>
            </div>`;
                // Append the custom field input to the container
                $('#custom-fields-container').append(customFieldInput);
            });

            // Remove Field button click event
            $('#custom-fields-container').on('click', '.remove-field', function() {
                $(this).parent().parent().remove();
            });

            // Form submission event
            $('form').on('submit', function() {
                // Collect and format custom fields as JSON
                const customFieldsArray = [];
                $('#custom-fields-container input[name^="custom_fields"]').each(function() {
                    const fieldData = $(this).attr('name_key').split('[');
                    const fieldIndex = fieldData[1];
                    const fieldName = fieldData[2].split(']')[0];
                    const fieldValue = $(this).val();
                    const isEnabled = $(`input[name="custom_fields[${fieldIndex}][enabled]"]`).prop(
                        'checked');
                    customFieldsArray.push({
                        name_data: fieldName,
                        name_key: $(`input[name="custom_fields[${fieldIndex}][name_key]"]`)
                            .val(),
                        name_value: fieldValue,
                        enabled: isEnabled,
                    });
                });

                customFields['custom_fields'] = customFieldsArray;
                $('#custom-fields-json').val(JSON.stringify(customFields)); // Serialize as JSON
            });
        });
    </script>

    {{-- sales fields --}}
    <script>
        $(document).ready(function() {
            let salesFieldsArray = [];
            $('div.row.mb-2').each(function() {
                const nameData = $(this).find('input[name="name_data[]"]').val();
                const nameKey = $(this).find('input[name="name_key[]"]').val();
                const nameValue = $(this).find('input[name="name_value[]"]').val();
                const enabled = $(this).find('input[name="enabled[]"]').prop('checked') ? '1' : '0';
                const disabled = '0';

                const fieldData = {
                    name_data: nameData,
                    name_key: nameKey,
                    name_value: nameValue,
                    enabled: enabled,
                    disabled: disabled,
                };

                salesFieldsArray.push(fieldData);
            });

            $('#sales-fields-json').val(JSON.stringify(salesFieldsArray));
        });
    </script>

    {{-- system fields --}}
    <script>
        $(document).ready(function() {
            let systemFieldsArray = [];
            $('div.row.mb-2').each(function() {
                const nameData = $(this).find('input[name="name_data[]"]').val();
                const nameKey = $(this).find('input[name="name_key[]"]').val();
                const nameValue = $(this).find('input[name="name_value[]"]').val();
                const enabled = $(this).find('input[name="enabled[]"]').prop('checked') ? '1' : '0';
                const disabled = '0'; // Assuming 'disabled' is always '0' based on your format

                const fieldData = {
                    name_data: nameData,
                    name_key: nameKey,
                    name_value: nameValue,
                    enabled: enabled,
                    disabled: disabled,
                };

                systemFieldsArray.push(fieldData);
            });

            $('#system-fields-json').val(JSON.stringify(systemFieldsArray));
        });
    </script>
@endsection





{{-- save data --}}
@section('scripts')
    <script>
        $(document).ready(function() {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return {
                        upload: function() {
                            return loader.file
                                .then(function(file) {
                                    return new Promise(function(resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST',
                                            '{{ route('admin.projects.storeCKEditorImages') }}',
                                            true);
                                        xhr.setRequestHeader('x-csrf-token', window._token);
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.responseType = 'json';

                                        // Init listeners
                                        var genericErrorText =
                                            `Couldn't upload file: ${ file.name }.`;
                                        xhr.addEventListener('error', function() {
                                            reject(genericErrorText)
                                        });
                                        xhr.addEventListener('abort', function() {
                                            reject()
                                        });
                                        xhr.addEventListener('load', function() {
                                            var response = xhr.response;

                                            if (!response || xhr.status !== 201) {
                                                return reject(response && response
                                                    .message ?
                                                    `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                    `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                                );
                                            }

                                            $('form').append(
                                                '<input type="hidden" name="ck-media[]" value="' +
                                                response.id + '">');

                                            resolve({
                                                default: response.url
                                            });
                                        });

                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(
                                                e) {
                                                if (e.lengthComputable) {
                                                    loader.uploadTotal = e.total;
                                                    loader.uploaded = e.loaded;
                                                }
                                            });
                                        }

                                        // Send request
                                        var data = new FormData();
                                        data.append('upload', file);
                                        data.append('crud_id', '{{ $project->id ?? 0 }}');
                                        xhr.send(data);
                                    });
                                })
                        }
                    };
                }
            }

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }
        });
    </script>
@endsection
