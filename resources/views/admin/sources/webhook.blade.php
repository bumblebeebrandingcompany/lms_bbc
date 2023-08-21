@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>
            @lang('messages.configure_webhook_details')
            <small>
                <strong>Source:</strong> 
                <span class="text-primary">{{$source->name}}</span> 
                <strong>Project:</strong>
                <span class="text-primary">{{optional($source->project)->name}}</span>
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
                            {{trans('messages.receive_webhook')}}
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
                                                {{ trans('messages.name') }}
                                                </td>
                                                <td>
                                                    {!!$lead->name ?? ''!!}
                                                </td>
                                            </tr>
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
                                                    @lang('messages.additional_email_key')
                                                </td>
                                                <td>
                                                    {{ $lead->additional_email ?? '' }}
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
                                            <tr>
                                                <td>
                                                    @lang('messages.secondary_phone_key')
                                                </td>
                                                <td>
                                                    {{ $lead->secondary_phone ?? '' }}
                                                </td>
                                            </tr>
                                            @php
                                                $lead_info = $lead->lead_info;
                                                if (
                                                    !empty($lead->source) && 
                                                    !empty($lead->source->name_key) && 
                                                    isset($lead_info[$lead->source->name_key]) &&
                                                    !empty($lead_info[$lead->source->name_key])
                                                ) {
                                                    unset($lead_info[$lead->source->name_key]);
                                                }
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

                                                if (
                                                    !empty($lead->source) && 
                                                    !empty($lead->source->additional_email_key) && 
                                                    isset($lead_info[$lead->source->additional_email_key]) &&
                                                    !empty($lead_info[$lead->source->additional_email_key])
                                                ) {
                                                    unset($lead_info[$lead->source->additional_email_key]);
                                                }

                                                if (
                                                    !empty($lead->source) && 
                                                    !empty($lead->source->secondary_phone_key) &&
                                                    isset($lead_info[$lead->source->secondary_phone_key]) &&
                                                    !empty($lead_info[$lead->source->secondary_phone_key])
                                                ) {
                                                    unset($lead_info[$lead->source->secondary_phone_key]);
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
                            $tags = optional($source->project)->webhook_fields ?? [];
                            $email_label = __('messages.email');
                            $phone_label = __('messages.phone');
                            $name_label = __('messages.name');
                            $addi_email_label = __('messages.additional_email');
                            $secondary_phone_label = __('messages.secondary_phone');
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name_key" class="required">
                                                    {{ trans('messages.name') }} {{trans('messages.key')}}
                                                </label><br>
                                                <select class="form-control select2" name="name_key" id="name_key" required>
                                                    <option value="">@lang('messages.please_select')</option>
                                                    @foreach($tags as $key)
                                                        <option value="{{$key}}"
                                                            @if(
                                                                ($key == $source->name_key) ||
                                                                (soundex($key) == soundex($name_label))
                                                            )
                                                                selected
                                                            @endif>
                                                            {{$key}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="email_key" class="required">
                                                    {{ trans('messages.email') }} {{trans('messages.key')}}
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
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="addi_email_label">
                                                    {{ trans('messages.additional_email_key') }} {{trans('messages.key')}}
                                                </label><br>
                                                <select class="form-control select2" name="additional_email_key" id="addi_email_label">
                                                    <option value="">@lang('messages.please_select')</option>
                                                    @foreach($tags as $key)
                                                        <option value="{{$key}}"
                                                            @if(
                                                                ($key == $source->additional_email_key) ||
                                                                (soundex($key) == soundex($addi_email_label))
                                                            )
                                                                selected
                                                            @endif>
                                                            {{$key}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="phone_key" class="required">
                                                {{ trans('messages.phone') }} {{trans('messages.key')}}
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
                                        <div class="col-md-4">
                                            <label for="secondary_phone_key">
                                                {{ trans('messages.secondary_phone_key') }} {{trans('messages.key')}}
                                            </label>
                                            <select class="form-control select2" name="secondary_phone_key" id="secondary_phone_key">
                                                <option value="">@lang('messages.please_select')</option>
                                                @foreach($tags as $key)
                                                    <option value="{{$key}}"
                                                        @if(
                                                            ($key == $source->secondary_phone_key) ||
                                                            (soundex($key) == soundex($secondary_phone_label))
                                                        )
                                                            selected
                                                        @endif>
                                                        {{$key}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
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
            }, 300);
        }

        $(document).on('click', '.refresh_latest_lead', function() {
            location.reload();
        });
    });
</script>
@endsection