<div class="form-group">
    <label for="source_field1">
        {{ trans('messages.source_field', ['num' => 1]) }}
    </label>
    <input class="form-control" type="text" name="source_field1" 
        id="source_field1" value="{{ $source->source_field1 ?? old('source_field1') }}">
    <span class="help-block">{{ trans('messages.source_custom_field_help_text', ['num' => 1]) }}</span>
</div>

<div class="form-group">
    <label for="source_field2">
        {{ trans('messages.source_field', ['num' => 2]) }}
    </label>
    <input class="form-control" type="text" name="source_field2" 
        id="source_field2" value="{{ $source->source_field2 ?? old('source_field2') }}">
    <span class="help-block">{{ trans('messages.source_custom_field_help_text', ['num' => 2]) }}</span>
</div>

<div class="form-group">
    <label for="source_field3">
        {{ trans('messages.source_field', ['num' => 3]) }}
    </label>
    <input class="form-control" type="text" name="source_field3" 
        id="source_field3" value="{{ $source->source_field3 ?? old('source_field3') }}">
    <span class="help-block">{{ trans('messages.source_custom_field_help_text', ['num' => 3]) }}</span>
</div>

<div class="form-group">
    <label for="source_field4">
        {{ trans('messages.source_field', ['num' => 4]) }}
    </label>
    <input class="form-control" type="text" name="source_field4" 
        id="source_field4" value="{{ $source->source_field4 ?? old('source_field4') }}">
    <span class="help-block">{{ trans('messages.source_custom_field_help_text', ['num' => 4]) }}</span>
</div>