@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.edit') }} {{ trans('messages.document') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.documents.update', [$document->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @includeIf('admin.documents.partials.form')
            <div class="form-group">
                <button class="btn btn-primary float-right" type="submit">
                    {{ trans('global.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
            return {
                upload: function() {
                return loader.file
                    .then(function (file) {
                    return new Promise(function(resolve, reject) {
                        // Init request
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', '{{ route('admin.documents.storeCKEditorImages') }}', true);
                        xhr.setRequestHeader('x-csrf-token', window._token);
                        xhr.setRequestHeader('Accept', 'application/json');
                        xhr.responseType = 'json';

                        // Init listeners
                        var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                        xhr.addEventListener('error', function() { reject(genericErrorText) });
                        xhr.addEventListener('abort', function() { reject() });
                        xhr.addEventListener('load', function() {
                        var response = xhr.response;

                        if (!response || xhr.status !== 201) {
                            return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                        }

                        $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                        resolve({ default: response.url });
                        });

                        if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                            loader.uploadTotal = e.total;
                            loader.uploaded = e.loaded;
                            }
                        });
                        }

                        // Send request
                        var data = new FormData();
                        data.append('upload', file);
                        data.append('crud_id', '{{ $document->id ?? 0 }}');
                        xhr.send(data);
                    });
                    })
                }
            };
            }
        }

        var allEditors = document.querySelectorAll('.ckeditor');
        for (var i = 0; i < allEditors.length; ++i) {
            ClassicEditor.create(
            allEditors[i], {
                extraPlugins: [SimpleUploadAdapter]
            }
            );
        }

        $(document).on('click', '.remove-file', function(){
            if (confirm('{{ trans('global.areYouSure') }}')) {
                let url = $(this).attr('data-href');
                let file = $(this).attr('data-file');
                let row = $(this).closest('tr');
                $.ajax({
					method:"DELETE",
					url: url,
					data: {file : file},
					dataType: "json",
					success: function(response) {
						if(response.success == true){
                            if($(row).closest('tbody').find('tr').length <= 1) {
                                $('.existing_files_div').remove();
                            } else{
                                $(row).remove();
                            }
						}
                        alert(response.message);
					}
				});
            }
        });
    });
</script>
@endsection