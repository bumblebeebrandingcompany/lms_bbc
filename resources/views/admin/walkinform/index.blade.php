@extends('layouts.admin')

@section('content')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2> Walkin {{ trans('global.list') }}</h2>
        </div>
    </div>

    <div class="card card-primary card-outline">
        @if (!auth()->user()->is_client)
            <div class="card-header">
                {{-- @if (auth()->user()->checkPermission('client_create')) --}}
                <a class="btn btn-success float-right" href="{{ route('admin.walkinform.create') }}">
                    {{ trans('global.add') }} Walkin
                </a>
                {{-- @endif --}}
            </div>
        @endif
        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-walkin">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Ref_num</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Additional Email</th>
                            <th>Phone</th>
                            <th>Secondary Phone</th>
                            {{-- <th>Project</th> --}}
                            {{-- <th>Campaign</th> --}}
                            <th>Source</th>
                            <th>Added By</th>
                            {{-- <th>Remarks</th> --}}
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1;
                    @endphp
                        @foreach ($walkins as $walkin)
                            <tr>
                                <td>{{ $counter++ }}</td>

                                {{-- @foreach ($walkin as $lead) --}}
                                <td>
                                    @foreach ($walkin->leads as $lead)
                                        {{ $lead->ref_num ?? '' }}
                                    @endforeach
                                </td>
                                <td> {{ $walkin->name }}</td>
                                <td> {{ $walkin->email }}</td>
                                <td> {{ $walkin->additional_email }}</td>
                                <td> {{ $walkin->phone }}</td>
                                <td> {{ $walkin->secondary_phone }}</td>
                                {{-- <td> {{ $walkin->project->name ?? '' }}</td> --}}
                                {{-- <td> {{ $walkin->campaign->campaign_name ?? '' }}</td> --}}
                                <td> {{ $walkin->subsource->source->name ?? '' }}</td>
                                <td>
                                    @foreach ($walkin->leads as $lead)
                                        {{ $lead->createdBy->representative_name ?? '' }}
                                    @endforeach
                                </td>
                                {{-- <td> {{ $walkin->remarks }}</td> --}}

                                <td>
                                    <div class="d-flex justify-content-between flex-nowrap">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <!-- Button to trigger the edit modal -->
                                                <a href="{{ route('admin.walkinform.show', $walkin->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                        @if (!auth()->user()->is_client)
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!-- Button to trigger the edit modal -->
                                                    <a href="{{ route('admin.walkinform.edit', $walkin->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        Edit
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <!-- Button to trigger the delete modal -->
                                                    <!-- Button to trigger the delete modal -->
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#deleteModal_{{ $walkin->id }}">
                                                        Delete
                                                    </button>

                                                    <!-- Delete Confirmation Modal -->
                                                    <div class="modal fade" id="deleteModal_{{ $walkin->id }}"
                                                        tabindex="-1" role="dialog"
                                                        aria-labelledby="deleteModalLabel_{{ $walkin->id }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="deleteModalLabel_{{ $walkin->id }}">Confirm
                                                                        Deletion</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you sure you want to delete this item?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Cancel</button>
                                                                    <!-- Form to handle the actual deletion -->
                                                                    <form
                                                                        action="{{ route('admin.walkinform.destroy', $walkin->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Delete</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        @endif
                                    </div>
            </div>

        </div>
        </td>
        {{-- @endforeach --}}
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(function() {
            // Existing JavaScript code for DataTable initialization

            // Additional customization for DataTable
            let table = $('.datatable-walkin').DataTable();
            table.on('draw.dt', function() {
                // Add any additional customization after the table is drawn
            });
        });
    </script>
@endsection
