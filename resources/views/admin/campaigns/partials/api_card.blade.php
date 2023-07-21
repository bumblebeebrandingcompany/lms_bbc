<div class="card border-secondary">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>
                        {{trans('messages.api_to_send_webhook')}} *
                    </label>
                    <input type="url" placeholder="{{trans('messages.api_to_send_webhook')}}" value="{{$api['url'] ?? ''}}" name="api[{{$key}}][url]" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{trans('messages.secret_key')}}
                        <i class="fas fa-info-circle" data-html="true" data-toggle="tooltip" title="{{trans('messages.12_char_random_str')}}"></i>
                    </label>
                    <input type="text" placeholder="{{trans('messages.secret_key')}}" value="{{$api['secret_key'] ?? ''}}" name="api[{{$key}}][secret_key]" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
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
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{trans('messages.headers')}}
                    </label>
                    <textarea  class="form-control" name="api[{{$key}}][headers]" rows="3">{{$api['headers'] ?? ''}}</textarea>
                    <small class="form-text text-muted">
                        {{trans('messages.headers_help_text')}} <br>
                        Ex: {"header-1" : "header 1 Value", "header-2" : "header 2 Value", "header3" : "header 3 Value"}
                    </small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                        {{trans('messages.request_body')}}
                    </label>
                    <textarea  class="form-control" name="api[{{$key}}][request_body]" rows="3">{{$api['request_body'] ?? ''}}</textarea>
                    <small class="form-text text-muted">
                        {{trans('messages.request_body_help_text')}} <br>
                        @if(!empty($tags))
                            <strong>
                                {{trans('messages.available_tags')}}:
                            </strong>
                            @foreach($tags as $tag)
                                {{'{'.$tag.'}'}} @if(!$loop->last) {{','}}@endif
                            @endforeach
                        @else
                            <strong>
                                {{trans('messages.send_webhook_request_to_view_tags')}}
                            </strong>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>