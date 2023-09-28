@if(!empty($document->files_url))
    <div class="row existing_files_div">
        <div class="col-md-12">
            <h4>@lang('messages.existing_files')</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            @lang('messages.file')
                        </th>
                        <th>
                            @lang('messages.action')
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($document->files_url as $file)
                        <tr>
                            <th>
                                {{$file['file_name']}} <br>
                                <a href="{{$file['url'] ?? '#!'}}" target="_blank"
                                    download="{{$file['file_name']}}" class="btn btn-outline-primary btn-sm m-2">
                                    <i class="fas fa-download mr-1"></i>
                                    {{$file['file_name']}}
                                </a>
                            </th>
                            <td>
                                <button type="button" data-href="{{route('admin.documents.remove.file', ['id' => $document->id])}}" class="btn btn-outline-danger btn-sm remove-file" data-file="{{$file['org_file_name']}}">
                                    <i class="fas fa-trash mr-1"></i>
                                    @lang('messages.remove')
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-12">
        <h4>@lang('messages.upload_files')</h4>
    </div>
    @for($i = 0; $i<10; $i++)
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="file_{{$i}}">
                    @lang('messages.choose_file')
                </label>
                <input type="file" name="files[{{$i}}]" class="form-control-file" id="file_{{$i}}">
            </div>
        </div>
    @endfor
</div>