<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label class="required">
                @lang('messages.key')
            </label>
            @if(isset($webhook_fields) && !empty($webhook_fields))
                <select name="lead_details[{{$index}}][key]" class="form-control select-tags" required>
                    <option value="">@lang('messages.please_select')</option>
                    @foreach($webhook_fields as $webhook_field)
                        <option value="{{$webhook_field}}"
                            @if(isset($key) && !empty($key) && $key == $webhook_field)
                                selected
                            @endif>
                            {{$webhook_field}}
                        </option>
                    @endforeach
                </select>
            @else
                <input type="text" name="lead_details[{{$index}}][key]" value="{{$key ?? ''}}" class="form-control" required @if(isset($set_key_readonly) && $set_key_readonly) readonly @endif>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="@if(isset($set_key_readonly) && $set_key_readonly) @else required @endif">
                @lang('messages.value')
            </label>
            <input type="text" name="lead_details[{{$index}}][value]" value="{{$value ?? ''}}" class="form-control" 
            
            @if(isset($set_key_readonly) && $set_key_readonly) @else required @endif>
        </div>
    </div>
    <div class="col-md-1 mt-auto mb-auto">
        <div class="form-group">
            <button type="button" class="btn btn-danger btn-sm float-right delete_lead_detail_row">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</div>