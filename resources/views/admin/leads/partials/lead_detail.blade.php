<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label class="required">
                @lang('messages.key')
            </label>
            <input type="text" name="lead_details[{{$index}}][key]" value="{{$key ?? ''}}" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="required">
                @lang('messages.value')
            </label>
            <input type="text" name="lead_details[{{$index}}][value]" value="{{$value ?? ''}}" class="form-control" required>
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