@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1>Update Walkin</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.walkinform.update', [$walkinform->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @foreach ($walkins as $walkinform)
                        @endforeach
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ $walkinform->name }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Email:</label>
                            <input type="text" name="email" class="form-control" value="{{ $walkinform->email }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Additional Email:</label>
                            <input type="text" name="additional_email" class="form-control"
                                value="{{ $walkinform->additional_email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="text" name="phone" class="form-control" value="{{ $walkinform->phone }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="secondary_phone">Secondary Phone:</label>
                            <input type="text" name="secondary_phone" class="form-control"
                                value="{{ $walkinform->secondary_phone }}">
                        </div>
                        <div class="form-group">
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
                            <span class="help-block">{{ trans('cruds.lead.fields.project_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="campaign_id">{{ trans('cruds.source.fields.campaign') }}</label>
                            <br>
                            <select class="form-control select2 {{ $errors->has('campaign_id') ? 'is-invalid' : '' }}"
                                name="campaign_id" id="campaign_id" required>
                                @foreach ($campaigns as $id => $entry)
                                    <option value="{{ $id }}" {{ old('campaign_id') == $id ? 'selected' : '' }}>
                                        {{ $entry }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('campaign_id'))
                                <span class="text-danger">{{ $errors->first('campaign_id') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.source.fields.campaign_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="source_id">{{ trans('messages.source') }}</label>
                            <br>
                            <select class="form-control select2 {{ $errors->has('source_id') ? 'is-invalid' : '' }}"
                                name="source_id" id="source_id" required>
                            </select>
                            <br>
                            @if ($errors->has('source_id'))
                                <span class="text-danger">{{ $errors->first('source_id') }}</span>
                            @endif
                        </div>

                        <input type="hidden" name="comments" class="form-control" value= "Direct Walk-in attended"
                            required>
                        <button type="submit" class="btn btn-success">Update Walkin</button>
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
                method:"GET",
                url: "{{route('admin.get.campaigns')}}",
                data: data,
                dataType: "json",
                success: function(response) {
                    $('#campaign_id').select2('destroy').empty().select2({data: response});
                    $('#campaign_id').val("{{$walkinform->campaign_id}}").change();
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
                    $('#source_id').val("{{$walkinform->source_id}}").change();
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
