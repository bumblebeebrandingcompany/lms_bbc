@extends('layouts.app')
@section('content')
<div class="row p-5">
    <div class="col-md-12">
        <h2 class="text-center text-bold text-primary">
            {{config('app.name', 'LMS')}}
        </h2>
    </div>
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    @if(!empty($document->files_url))
                        <div class="col-md-12 mb-3">
                            <label for="files">@lang('messages.files'):</label>
                            @foreach($document->files_url as $file)
                                <a href="{{$file['url'] ?? '#!'}}" target="_blank"
                                    download="{{$file['file_name']}}" class="btn btn-outline-primary btn-sm m-2">
                                    <i class="fas fa-download mr-1"></i>
                                    {{$file['file_name']}}
                                </a>
                            @endforeach
                            <hr>
                        </div>
                    @endif
                    <div class="col-md-12 mb-4">
                        <h3>
                            {!!$document->title!!}
                        </h3>
                    </div>
                    <div class="col-md-12">
                        {!!$document->details!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection