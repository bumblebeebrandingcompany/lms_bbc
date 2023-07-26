<div class="card border-secondary" data-key="{{$key}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-7">
                <div class="form-group">
                    <label>
                        {{trans('messages.url_to_send_webhook')}} *
                    </label>
                    <input type="url" placeholder="{{trans('messages.url_to_send_webhook')}}" value="{{$webhook['url'] ?? ''}}" name="webhook[{{$key}}][url]" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{trans('messages.secret_key')}}
                        <i class="fas fa-info-circle" data-html="true" data-toggle="tooltip" title="{{trans('messages.12_char_random_str')}}"></i>
                    </label>
                    <input type="text" placeholder="{{trans('messages.secret_key')}}" value="{{$webhook['secret_key'] ?? ''}}" name="webhook[{{$key}}][secret_key]" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>
                        {{trans('messages.method')}} *
                    </label>
                    <select name="webhook[{{$key}}][method]" class="form-control">
                        <option value="get"
                            @if(
                                isset($webhook['method']) && 
                                !empty($webhook['method']) &&
                                $webhook['method'] == 'get'
                            )
                                selected
                            @endif>
                            GET
                        </option>
                        <option value="post"
                            @if(
                                isset($webhook['method']) && 
                                !empty($webhook['method']) &&
                                $webhook['method'] == 'post'
                            )
                                selected
                            @endif>
                            POST
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-danger btn-sm float-right delete_webhook">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</div>