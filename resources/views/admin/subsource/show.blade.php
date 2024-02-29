@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>Show SubSource</h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-header">
        <a class="btn btn-default float-right" href="{{ route('admin.sources.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.id') }}
                        </th>
                        <td>
                            {{ $subsource->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.project') }}
                        </th>
                        <td>
                            {{ $subsource->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.campaign') }}
                        </th>
                        <td>
                            {{ $subsource->campaign->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('messages.name') }}
                            <i class="fas fa-info-circle" data-html="true"
                                data-toggle="tooltip"
                                title="{{trans('messages.name_help_text')}}">
                            </i>
                        </th>
                        <td>
                            {{ $subsource->source->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
SubSource Name                        </th>
                        <td>
                            {{ $subsource->name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
