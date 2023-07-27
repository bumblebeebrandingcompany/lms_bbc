@extends('layouts.admin')
@section('styles')
<style>
    textarea {
        min-height: auto;
    }
</style>
@endsection
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.show') }} {{ trans('messages.webhook_details') }}
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
                            {{trans('messages.receive_webhook')}}
                        </h3>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-default float-right" href="{{ route('admin.campaigns.index') }}">
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
                                    <input type="text" id="webhook_url" value="{{route('webhook.processor', ['secret' => $source->webhook_secret])}}" class="form-control cursor-pointer copy_link" readonly>
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
                                {{trans('messages.most_recent_lead')}}
                            </h3>
                            <button type="button" class="btn btn-outline-primary btn-xs refresh_latest_lead">
                                <i class="fas fa-sync"></i>
                                {{trans('messages.refresh')}}
                            </button>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{trans('messages.key')}}</th>
                                            <th>{{trans('messages.value')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($lead) && !empty($lead->lead_info))
                                            @php
                                                $serial_num = 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                {{ trans('messages.email') }}
                                                </td>
                                                <td>
                                                    {!!$lead->email ?? ''!!}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                {{ trans('messages.phone') }}
                                                </td>
                                                <td>
                                                {!!$lead->phone ?? ''!!}
                                                </td>
                                            </tr>
                                            @php
                                                $lead_info = $lead->lead_info;
                                                if (
                                                    !empty($lead->source) && 
                                                    !empty($lead->source->email_key) && 
                                                    isset($lead_info[$lead->source->email_key]) &&
                                                    !empty($lead_info[$lead->source->email_key])
                                                ) {
                                                    unset($lead_info[$lead->source->email_key]);
                                                }

                                                if (
                                                    !empty($lead->source) && 
                                                    !empty($lead->source->phone_key) &&
                                                    isset($lead_info[$lead->source->phone_key]) &&
                                                    !empty($lead_info[$lead->source->phone_key])
                                                ) {
                                                    unset($lead_info[$lead->source->phone_key]);
                                                }
                                            @endphp
                                            @foreach($lead_info as $key => $value)
                                                @php
                                                    $serial_num = $loop->iteration;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        {!!$key!!}
                                                    </td>
                                                    <td>
                                                        {!!$value!!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>
                                                    {{trans('messages.created_at')}}
                                                </td>
                                                <td>
                                                    {{\Carbon\Carbon::parse($lead->created_at)->diffForHumans()}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {{trans('messages.updated_at')}}
                                                </td>
                                                <td>
                                                    {{\Carbon\Carbon::parse($lead->updated_at)->diffForHumans()}}
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <span class="text-center">
                                                        {{trans('messages.no_data_found')}}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @php
                            $tags = !empty($lead->lead_info) ? array_keys($lead->lead_info) : [];
                            $email_label = __('messages.email');
                            $phone_label = __('messages.phone');
                        @endphp
                        <div class="col-md-12">
                            <h3>
                                @lang('messages.email_and_phone_key')
                            </h3>
                        </div>
                        <div class="row ml-1">
                            <div class="col-md-12">
                                <form action="{{route('admin.update.email.and.phone.key')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="source_id" value="{{$source->id}}" id="source_id">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email_key">
                                                    {{ trans('messages.email') }} {{trans('messages.key')}} *
                                                </label><br>
                                                <select class="form-control select2" name="email_key" id="email_key" required>
                                                    <option value="">@lang('messages.please_select')</option>
                                                    @foreach($tags as $key)
                                                        <option value="{{$key}}"
                                                            @if(
                                                                ($key == $source->email_key) ||
                                                                (soundex($key) == soundex($email_label))
                                                            )
                                                                selected
                                                            @endif>
                                                            {{$key}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone_key">
                                                {{ trans('messages.phone') }} {{trans('messages.key')}} *
                                            </label>
                                            <select class="form-control select2" name="phone_key" id="phone_key" required>
                                                <option value="">@lang('messages.please_select')</option>
                                                @foreach($tags as $key)
                                                    <option value="{{$key}}"
                                                        @if(
                                                            ($key == $source->phone_key) ||
                                                            (soundex($key) == soundex($phone_label))
                                                        )
                                                            selected
                                                        @endif>
                                                        {{$key}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-outline-primary">
                                                {{trans('messages.save')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    {{trans('messages.send_webhook')}}
                </h3>
            </div>
            <form action="{{route('admin.source.outgoing.webhook.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <input type="hidden" name="source_id" value="{{$source->id}}">
                    <!-- <h4>
                        {{trans('messages.outgoing_webhook')}}
                    </h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group outgoing_webhook">
                                @php
                                    $webhooks = $source->outgoing_webhook ?? [];
                                    $total_webhook = !empty($webhooks) ? count($webhooks) : 1;
                                @endphp
                                @forelse($webhooks as $key => $webhook)
                                    @includeIf('admin.sources.partials.webhook_card', ['key' => $key, 'webhook' => $webhook])
                                @empty
                                    @for($i = 0; $i<=0 ; $i++)
                                        @includeIf('admin.sources.partials.webhook_card', ['key' => $i, 'webhook' => []])
                                    @endfor
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-outline-primary float-right add_outgoing_webhook"
                                data-total="{{$total_webhook}}">
                                @lang('messages.add_outgoing_webhook')
                            </button>
                        </div>
                    </div>
                    <hr> -->
                    <h4>
                        {{trans('messages.outgoing_webhook')}}
                    </h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group outgoing_api">
                                @php
                                    $apis = $source->outgoing_apis ?? [];
                                    $api_webhook_key = 0;
                                @endphp
                                @forelse($apis as $key => $api)
                                    @php
                                        $api_webhook_key = $key;
                                    @endphp
                                    @includeIf('admin.sources.partials.api_card', ['key' => $key, 'api' => $api, 'tags' => $tags])
                                @empty
                                    @for($i = 0; $i<=0 ; $i++)
                                        @php
                                            $api_webhook_key = $i;
                                        @endphp
                                        @includeIf('admin.sources.partials.api_card', ['key' => $i, 'api' => [], 'tags' => $tags])
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
            }, 3000);
        }

        $(document).on('click', '.refresh_latest_lead', function() {
            location.reload();
        });

        // $(document).on('click', '.add_outgoing_webhook', function() {
        //     let key = $(this).attr('data-total');
        //     $.ajax({
        //         method:"GET",
        //         url: "{{route('admin.source.webhook.html')}}",
        //         data: {
        //             type: 'webhook',
        //             key: key,
        //         },
        //         dataType: "html",
        //         success: function(response) {
        //             $("div.outgoing_webhook").append(response);
        //             $(".add_outgoing_webhook").attr('data-total', +key + 1);
        //         }
        //     });
        // });

        $(document).on('click', '.add_outgoing_api', function() {
            let key = $(this).attr('data-api_webhook_key');
            let source_id = $("#source_id").val();
            $.ajax({
                method:"GET",
                url: "{{route('admin.source.webhook.html')}}",
                data: {
                    type: 'api',
                    key: parseInt(key)+1,
                    source_id: source_id
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
            let source_id = $("#source_id").val();
            let rb_key = $(this).attr('data-rb_key');
            $.ajax({
                method:"GET",
                url: "{{route('admin.get.req.body.row.html')}}",
                data: {
                    source_id: source_id,
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
    });
</script>
@endsection