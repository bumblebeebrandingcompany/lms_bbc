@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>Walkin</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <a class="btn btn-default float-right" href="{{ route('admin.walkinform.index') }}">
                        <i class="fas fa-chevron-left"></i>
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Ref Num</th>
                                    <td>
                                        @foreach ($walkins as $walkin)
                                        @endforeach
                                        @foreach ($walkin->leads as $lead)
                                        {{ $lead->ref_num ?? '' }}
                                    @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('cruds.client.fields.name') }}</th>
                                    <td>{{ $walkin->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $walkin->email ?? '' }}</td>
                                </tr>
                                <tr>
                                    <tr>
                                        <th> Secondary Email</th>
                                        <td>{{ $walkin->additional_email ?? '' }}</td>
                                    </tr>
                                    <tr>
                                    <th>Phone</th>
                                    <td class="word-break">{{ $walkin->phone ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Secondary Phone</th>
                                    <td class="word-break">{{ $walkin->secondary_phone ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Project</th>
                                    <td class="word-break">{{ $walkin->projects->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Campaign</th>
                                    <td class="word-break">{{ $walkin->campaigns->campaign_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Source</th>
                                    <td class="word-break">{{ $walkin->sources->name ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
