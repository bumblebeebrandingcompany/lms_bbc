@extends('layouts.admin')
@section('content')

<div class="row mb-2">
   <div class="col-sm-6">
        <h2>
            {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
        </h2>
   </div>
</div>
<div class="card card-primary card-outline">
    <div class="card-header">
        <a class="btn btn-default float-right" href="{{ route('admin.audit-logs.index') }}">
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
                            {{ trans('cruds.auditLog.fields.id') }}
                        </th>
                        <td>
                            {{ $auditLog->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.description') }}
                        </th>
                        <td>
                            {{ $auditLog->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.subject_id') }}
                        </th>
                        <td>
                            {{ $auditLog->subject_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.subject_type') }}
                        </th>
                        <td>
                            {{ $auditLog->subject_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.user_id') }}
                        </th>
                        <td>
                            {{ $auditLog->user_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.properties') }}
                        </th>
                        <td>
                            {{ $auditLog->properties }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.host') }}
                        </th>
                        <td>
                            {{ $auditLog->host }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.auditLog.fields.created_at') }}
                        </th>
                        <td>
                            {{ $auditLog->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection