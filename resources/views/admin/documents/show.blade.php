@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.show') }} {{ trans('messages.document') }}
        </h2>
   </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a class="btn btn-default float-right" href="{{ route('admin.documents.index') }}">
                    <i class="fas fa-chevron-left"></i>
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ trans('cruds.lead.fields.project') }}
                                </th>
                                <td>
                                    {{ $document->project->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    @lang('messages.title')
                                </th>
                                <td>
                                    {{ $document->title }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('messages.details') }}
                                </th>
                                <td>
                                    {!! $document->details !!}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('messages.added_by') }}
                                </th>
                                <td>
                                    {{ $document->createdBy->name ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ trans('messages.files') }}
                                </th>
                                <td>
                                    @if(!empty($document->files_url))
                                        @foreach($document->files_url as $file)
                                            <a href="{{$file['url'] ?? '#!'}}" target="_blank"
                                                download="{{$file['file_name']}}" class="btn btn-outline-primary btn-sm m-2">
                                                <i class="fas fa-download mr-1"></i>
                                                {{$file['file_name']}}
                                            </a>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection