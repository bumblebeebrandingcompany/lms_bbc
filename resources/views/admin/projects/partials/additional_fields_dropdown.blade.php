@php
    $webhook_fields = $project->webhook_fields ?? [];
@endphp
<label for="additional_columns_to_export">
    @lang('messages.additional_columns_to_export')
</label>
<select class="form-control" name="additional_columns_to_export" id="additional_columns_to_export" multiple>
    @foreach($webhook_fields as $webhook_field)
        <option value="{{ $webhook_field }}">{{ $webhook_field }}</option>
    @endforeach
</select>