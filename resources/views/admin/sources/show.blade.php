@extends('layouts.admin')
@section('content')
<div class="row mb-2">
   <div class="col-sm-12">
        <h2>
            {{ trans('global.show') }} {{ trans('cruds.source.title') }}
        </h2>
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
                            {{ $source->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.project') }}
                        </th>
                        <td>
                            {{ $source->project->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.campaign') }}
                        </th>
                        <td>
                            {{ $source->campaign->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.source.fields.name') }}
                        </th>
                        <td>
                            {{ $source->name }}
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
                            {{ $source->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('messages.source_field', ['num' => 1]) }}
                            <i class="fas fa-info-circle" data-html="true"
                                data-toggle="tooltip"
                                title="{{ trans('messages.source_custom_field_help_text', ['num' => 1]) }}">
                            </i>
                        </th>
                        <td>
                            {{ $source->source_field1 ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('messages.source_field', ['num' => 2]) }}
                            <i class="fas fa-info-circle" data-html="true"
                                data-toggle="tooltip"
                                title="{{ trans('messages.source_custom_field_help_text', ['num' => 2]) }}">
                            </i>
                        </th>
                        <td>
                            {{ $source->source_field2 ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('messages.source_field', ['num' => 3]) }}
                            <i class="fas fa-info-circle" data-html="true"
                                data-toggle="tooltip"
                                title="{{ trans('messages.source_custom_field_help_text', ['num' => 3]) }}">
                            </i>
                        </th>
                        <td>
                            {{ $source->source_field3 ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('messages.source_field', ['num' => 4]) }}
                            <i class="fas fa-info-circle" data-html="true"
                                data-toggle="tooltip"
                                title="{{ trans('messages.source_custom_field_help_text', ['num' => 4]) }}">
                            </i>
                        </th>
                        <td>
                            {{ $source->source_field4 ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
