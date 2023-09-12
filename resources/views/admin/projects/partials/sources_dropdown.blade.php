<label for="source_id">
    @lang('messages.source')
</label>
<select class="form-control" id="source_id">
    <option value>{{ trans('global.all') }}</option>
    @foreach($sources as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
    @endforeach
</select>