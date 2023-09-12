<label for="campaign_id">
    @lang('messages.campaigns')
</label>
<select class="form-control" id="campaign_id">
    <option value>{{ trans('global.all') }}</option>
    @foreach($campaigns as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
    @endforeach
</select>