@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>
          Create Promo
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route("admin.promo.store") }}" enctype="multipart/form-data">
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
                                {{ $entry->name }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <br>
                @if ($errors->has('source_id'))
                    <span class="text-danger">{{ $errors->first('source_id') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="name">Promo name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">Name of the promo</span>
            </div>

            {{-- <div class="form-group">
                <label class="required" for="source_name">{{ trans('messages.source_name') }}</label>
                <input class="form-control {{ $errors->has('source_name') ? 'is-invalid' : '' }}" type="text" name="source_name" id="source_name" value="{{ old('source_name') }}" required>
                @if($errors->has('source_name'))
                    <span class="text-danger">{{ $errors->first('source_name') }}</span>
                @endif
                <span class="help-block">{{ trans('messages.source_name_help_text') }}</span>
            </div> --}}
            {{-- @includeIf('admin.sources.partials.custom_fields') --}}
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
    });
</script>
@endsection
