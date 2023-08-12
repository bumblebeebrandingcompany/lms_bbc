<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label>
                @lang('messages.key')
            </label>
            <input type="text" name="api[{{$webhook_key}}][constants][{{$constant_key}}][key]" value="{{$constant['key'] ?? ''}}" class="form-control input">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>
                @lang('messages.value')
            </label>
            <input type="text" name="api[{{$webhook_key}}][constants][{{$constant_key}}][value]" value="{{$constant['value'] ?? ''}}" class="form-control input">
        </div>
    </div>
    <div class="col-md-1 mt-auto mb-auto">
        <div class="form-group">
            <button type="button" class="btn btn-danger btn-sm float-right delete_constant_row">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>
</div>