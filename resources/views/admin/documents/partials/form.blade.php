<div class="form-group">
    <label for="project_id">{{ trans('cruds.lead.fields.project') }}</label>
    <select class="form-control select2" name="project_id" id="project_id">
        <option value="">@lang('messages.please_select')</option>
        @foreach($projects as $id => $entry)
            <option value="{{ $id }}" 
            @if(
                (
                    old('project_id') == $id
                ) ||
                (
                    !empty($document->project_id) &&
                    ($document->project_id == $id)
                )
            )
                selected
            @endif
            >
                {{ $entry }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="title" class="required">
        @lang('messages.title')
    </label>
    <input type="text" name="title" id="title" value="{{ $document->title ?? old('title', '') }}" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" required>
    @if($errors->has('title'))
        <span class="text-danger">{{ $errors->first('title') }}</span>
    @endif
</div>
<div class="form-group">
    <label for="details">{{ trans('messages.details') }}</label>
    <textarea name="details" class="form-control ckeditor {{ $errors->has('details') ? 'is-invalid' : '' }}" id="details" rows="2">{!! $document->details ?? old('details', '') !!}</textarea>
    @if($errors->has('details'))
        <span class="text-danger">{{ $errors->first('details') }}</span>
    @endif
</div>
@includeIf('admin.documents.partials.files')