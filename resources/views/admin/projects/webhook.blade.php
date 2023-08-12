@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>
            @lang('messages.configure_webhook_details')
            <small>
                <strong>Project:</strong>
                <span class="text-primary">{{$project->name}}</span>
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
                            {{trans('messages.send_webhook')}}
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-default float-right" href="{{ route('admin.projects.index') }}">
                            <i class="fas fa-chevron-left"></i>
                            {{ trans('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
            <form action="{{route('admin.project.outgoing.webhook.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                @php
                    $db_fields = \App\Models\Lead::DEFAULT_WEBHOOK_FIELDS;
                    $tags = !empty($project->webhook_fields) ? array_unique(array_merge($project->webhook_fields, $db_fields)) : $db_fields;
                @endphp
                <div class="card-body">
                    <input type="hidden" name="project_id" value="{{$project->id}}" id="project_id">
                    <h4>
                        {{trans('messages.outgoing_webhook')}}
                    </h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group outgoing_api">
                                @php
                                    $apis = $project->outgoing_apis ?? [];
                                    $api_webhook_key = 0;
                                @endphp
                                @forelse($apis as $key => $api)
                                    @php
                                        $api_webhook_key = $key;
                                    @endphp
                                    @includeIf('admin.projects.partials.api_card', ['key' => $key, 'api' => $api, 'tags' => $tags])
                                @empty
                                    @for($i = 0; $i<=0 ; $i++)
                                        @php
                                            $api_webhook_key = $i;
                                        @endphp
                                        @includeIf('admin.projects.partials.api_card', ['key' => $i, 'api' => [], 'tags' => $tags])
                                    @endfor
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-primary add_outgoing_api"
                                data-api_webhook_key="{{$api_webhook_key}}">
                                @lang('messages.add_outgoing_webhook')
                            </button>
                            <button type="submit" class="btn btn-primary float-right">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(function() {
        $(document).on('click', '.add_outgoing_api', function() {
            let key = $(this).attr('data-api_webhook_key');
            let project_id = $("#project_id").val();
            $.ajax({
                method:"GET",
                url: "{{route('admin.projects.webhook.html')}}",
                data: {
                    type: 'api',
                    key: parseInt(key)+1,
                    project_id: project_id
                },
                dataType: "html",
                success: function(response) {
                    $("div.outgoing_api").append(response);
                    $(".add_outgoing_api").attr('data-api_webhook_key', +key + 1);
                    $(".select-tags").select2();
                }
            });
        });

        $(document).on('click', '.delete_api_webhook, .delete_webhook', function() {
            if(confirm('Do you want to remove?')) {
                $(this).closest('.card').remove();
            }
        });

        $(document).on('click', '.add_request_body_row', function() {
            let webhook_key = $(this).attr('data-webhook_key');
            let request_body_div = $(this).closest('.card').find('.request_body');
            let btn = $(this);
            let project_id = $("#project_id").val();
            let rb_key = $(this).attr('data-rb_key');
            $.ajax({
                method:"GET",
                url: "{{route('admin.get.req.body.row.html')}}",
                data: {
                    project_id: project_id,
                    webhook_key: webhook_key,
                    rb_key: parseInt(rb_key)+1
                },
                dataType: "html",
                success: function(response) {
                    request_body_div.append(response);
                    btn.attr('data-rb_key', +rb_key + 1);
                    $(".select-tags").select2();
                }
            });
        });

        $(document).on('click', '.delete_request_body_row', function() {
            if(confirm('Do you want to remove?')) {
                $(this).closest('.row').remove();
            }
        });

        $(document).on('click', '.test_webhook', function() {
            let card_id = $(this).attr('data-card_id');
            let data = {};
            $(`#${card_id} .input`).each(function() {
                data[$(this).attr('name')] = $(this).val();
            });
            $.ajax({
                method:"POST",
                url:"{{route('admin.projects.test.webhook')}}",
                data:data,
                dataType: "JSON",
                success: function(response) {
                    if(response.msg) {
                        alert(decodeURIComponent(response.msg));
                    }
                }
            })
        });

        $(document).on('click', '.add_constant_row', function() {
            let webhook_key = $(this).attr('data-webhook_key');
            let api_constant_div = $(this).closest('.card').find('.api_constant');
            let btn = $(this);
            let constant_key = $(this).attr('data-constant_key');
            $.ajax({
                method:"GET",
                url: "{{route('admin.get.api.constant.row.html')}}",
                data: {
                    webhook_key: webhook_key,
                    constant_key: parseInt(constant_key)+1
                },
                dataType: "html",
                success: function(response) {
                    api_constant_div.append(response);
                    btn.attr('data-constant_key', +constant_key + 1);
                }
            });
        });

        $(document).on('click', '.delete_constant_row', function() {
            if(confirm('Do you want to remove?')) {
                $(this).closest('.row').remove();
            }
        });
    });
</script>
@endsection