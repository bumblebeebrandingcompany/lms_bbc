<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label class="required">
                @lang('messages.key')
            </label>
            <input type="text" name="api[{{$webhook_key}}][request_body][{{$rb_key}}][key]" value="{{$rb['key'] ?? ''}}" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="required">
                @lang('messages.value')
            </label>
            <select class="form-control select2 select-tags" name="api[{{$webhook_key}}][request_body][{{$rb_key}}][value][]" required multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag }}"
                        @if(!empty($rb['value']) && is_array($rb['value']) && in_array($tag, $rb['value']))
                            selected
                        @endif>{{ $tag }}</option>
                @endforeach
            </select>
            @if(empty($tags))
                <strong>
                    {{trans('messages.send_webhook_request_to_view_tags')}}
                </strong>
            @endif
        </div>
    </div>
    <div class="col-md-1 mt-auto mb-auto">
        <div class="form-group">
            <button type="button" class="btn btn-danger btn-sm float-right delete_request_body_row">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</div>