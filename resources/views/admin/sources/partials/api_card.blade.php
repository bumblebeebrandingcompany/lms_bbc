<div class="card border-secondary mb-4" data-key="{{$key}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>
                        {{trans('messages.name')}} *
                    </label>
                    <input type="text" placeholder="{{trans('messages.name')}}" value="{{$api['name'] ?? ''}}" name="api[{{$key}}][name]" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>
                        {{trans('messages.url_to_send_webhook')}} *
                    </label>
                    <input type="url" placeholder="{{trans('messages.api_to_send_webhook')}}" value="{{$api['url'] ?? ''}}" name="api[{{$key}}][url]" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>
                        {{trans('messages.secret_key')}}
                        <i class="fas fa-info-circle" data-html="true" data-toggle="tooltip" title="{{trans('messages.12_char_random_str')}}"></i>
                    </label>
                    <input type="text" placeholder="{{trans('messages.secret_key')}}" value="{{$api['secret_key'] ?? ''}}" name="api[{{$key}}][secret_key]" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>
                        {{trans('messages.method')}} *
                    </label>
                    <select name="api[{{$key}}][method]" class="form-control">
                        <option value="get"
                            @if(
                                isset($api['method']) && 
                                !empty($api['method']) &&
                                $api['method'] == 'get'
                            )
                                selected
                            @endif>
                            GET
                        </option>
                        <option value="post"
                            @if(
                                isset($api['method']) && 
                                !empty($api['method']) &&
                                $api['method'] == 'post'
                            )
                                selected
                            @endif>
                            POST
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{trans('messages.headers')}}
                    </label>
                    <textarea  class="form-control" name="api[{{$key}}][headers]" rows="1">{{$api['headers'] ?? ''}}</textarea>
                    <small class="form-text text-muted">
                        {{trans('messages.headers_help_text')}} <br>
                        Ex: {"header-1" : "header 1 Value", "header-2" : "header 2 Value", "header3" : "header 3 Value"}
                    </small>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group request_body">
                    <label>
                        {{trans('messages.request_body')}}
                    </label>
                    @php
                        $rb_key = 0;
                    @endphp

                    @if(!empty($api['request_body']))
                        @foreach($api['request_body'] as $value)
                            @php
                                $rb_key = $loop->index;
                            @endphp
                            @includeIf('admin.sources.partials.request_body_input', [
                                'webhook_key' => $key,
                                'rb_key' => $rb_key,
                                'tags' => $tags,
                                'rb' => $value
                            ])
                        @endforeach
                    @else
                        @includeIf('admin.sources.partials.request_body_input', [
                            'webhook_key' => $key,
                            'rb_key' => $rb_key,
                            'tags' => $tags,
                            'rb' => []
                        ])
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary btn-sm add_request_body_row"
                    data-rb_key="{{$rb_key}}" data-webhook_key="{{$key}}">
                    @lang('messages.add_request_body_detail')
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm float-right delete_api_webhook mr-2">
                    <i class="fas fa-trash-alt"></i> @lang('messages.remove_webhook')
                </button>
            </div>
        </div>
    </div>
</div>