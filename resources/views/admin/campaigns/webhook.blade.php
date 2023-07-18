@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.show') }} {{ trans('messages.webhook_details') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-header">
        <a class="btn btn-default float-right" href="{{ route('admin.campaigns.index') }}">
            <i class="fas fa-chevron-left"></i>
            {{ trans('global.back_to_list') }}
        </a>
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
                            <input type="text" id="webhook_url" value="{{route('webhook.processor', ['secret' => $campaign->webhook_secret])}}" class="form-control cursor-pointer copy_link" readonly>
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
                                    <th>#</th>
                                    <th>{{trans('messages.label')}}</th>
                                    <th>{{trans('messages.value')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($lead) && !empty($lead->lead_details))
                                    @php
                                        $serial_num = 0;
                                    @endphp
                                    @foreach($lead->lead_details as $key => $value)
                                        @php
                                            $serial_num = $loop->iteration;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{$loop->iteration}}
                                            </td>
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
                                            {{$serial_num + 1}}
                                        </td>
                                        <td>
                                            {{trans('messages.created_at')}}
                                        </td>
                                        <td>
                                            {{\Carbon\Carbon::parse($lead->created_at)->diffForHumans()}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{$serial_num + 2}}
                                        </td>
                                        <td>
                                            {{trans('messages.updated_at')}}
                                        </td>
                                        <td>
                                            {{\Carbon\Carbon::parse($lead->updated_at)->diffForHumans()}}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">
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
    });
</script>
@endsection