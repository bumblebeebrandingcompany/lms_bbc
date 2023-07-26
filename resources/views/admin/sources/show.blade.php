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
                            {{ $source->campaign->campaign_name ?? '' }}
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
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection