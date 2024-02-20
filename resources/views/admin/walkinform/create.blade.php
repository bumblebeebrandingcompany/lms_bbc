@extends('layouts.admin')

{{-- <div class="modal fade" id="existingLeadModal" tabindex="-1" role="dialog" aria-labelledby="existingLeadModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="existingLeadModalLabel">Existing Lead Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach ($leads as $lead)
                    <div id="existingLeadDetails_{{ $lead->id }}">
                        <p>Lead ID: {{ $lead->id }}</p> <!-- Add more details as needed -->
                    </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> --}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Create Walkin</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.walkinform.store') }}" method="post">
                        @csrf
                        <div class="form-group" class="required">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group" class="required">
                            <label for="phone">Phone:</label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone') ? old('phone') : $phone ?? '' }}" class="form-control input_number"
                                @if (!auth()->user()->is_superadmin) required @endif>
                        </div>
                        <div class="form-group" class="required">
                            <label for="secondary_phone">Secondary Phone:</label>
                            <input type="phone" name="secondary_phone" class="form-control" required>
                        </div>
                        <div class="form-group" class="required">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group" class="required">
                            <label for="additional_email">Additional Email:</label>
                            <input type="email" name="additional_email" class="form-control" required>
                        </div>

                        <input type="hidden" name="comments" class="form-control" value= "Direct Walk-in attended"
                            required>
                        @if (!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
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
                                <label class="required"
                                    for="campaign_id">{{ trans('cruds.source.fields.campaign') }}</label>
                                <br>
                                <select class="form-control select2 {{ $errors->has('campaign') ? 'is-invalid' : '' }}"
                                    name="campaign_id" id="campaign_id" required>
                                    {{-- Outputting $campaigns array for debugging --}}
                                    {{ var_dump($campaigns) }}

                                    {{-- Loop through $campaigns and exclude option with ID 12 --}}
                                    @foreach ($campaigns as $id => $entry)
                                        @if ($id != 12)
                                            <option value="{{ $id }}"
                                                {{ old('campaign_id') == $id ? 'selected' : '' }}>
                                                {{ $entry }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @if ($errors->has('campaign'))
                                    <span class="text-danger">{{ $errors->first('campaign') }}</span>
                                @endif
                                <span class="help-block">{{ trans('cruds.source.fields.campaign_helper') }}</span>
                            </div>
                            <div class="form-group">
                                <label class="required" for="source_id">{{ trans('messages.source') }}</label>
                                <br>
                                <select class="form-control select2 {{ $errors->has('source_id') ? 'is-invalid' : '' }}"
                                        name="source_id" id="source_id" required>
                                    @foreach ($sources as $id => $entry)
                                        @if ($id != 25)
                                            <option value="{{ $id }}" {{ old('source_id') == $id ? 'selected' : '' }}>
                                                {{ $entry }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <br>
                                @if ($errors->has('source_id'))
                                    <span class="text-danger">{{ $errors->first('source_id') }}</span>
                                @endif
                            </div>
                            <br>
                        @endif
                        <button type="submit" class="btn btn-success">Create Walkin</button>
                    </form>
                </div>
            </div>
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
                getLeadDetailsRowHtml();
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

            function getLeadDetailsRowHtml() {
                $.ajax({
                    method: "GET",
                    url: "{{ route('admin.lead.details.rows') }}",
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
            getCampaigns();
        });
    </script>

{{-- <script>
    $(document).ready(function () {
        @if ($errors->has('lead_exists'))
            // Display the existing lead details modal if the 'lead_exists' error exists
            $('#existingLeadModal').modal('show');

            // Retrieve and display existing lead details in the modal body
            $.ajax({
                method: "GET",
                url: "{{ route('admin.lead.details.rows') }}", // Update with your route
                data: {

                    // email: $('#email').val(), // Assuming 'email' is the input field name
                    // phone: $('#phone').val()
                },
                dataType: "html",
                success: function (response) {
                    $("#existingLeadDetails").html(response);
                }
            });
        @endif
    });
</script> --}}
@endsection
