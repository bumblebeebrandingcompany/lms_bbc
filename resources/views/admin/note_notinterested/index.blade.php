@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Create Note</h2>

                </div>
                <div class="spacer"></div>
                <div class="spacer"></div>
                <form method="post" action="{{ route('admin.notenotinterested.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <input type="text" name="notes" id="notes" required class="form-control"
                                    placeholder="Enter a Notes for not interested....">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                @if(!auth()->user()->is_client)
                                <button type="submit"
                                    class="d-flex justify-content-between align-items-center btn btn-success ml-auto">Create
                                    Notes</button>
                                    @endif
                            </div>
                        </div>
                    </div>
                </form>
                <h2 class="mt-3">Notes</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Notes</th>
                            @if(!auth()->user()->is_client)
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($noteNotInterested as $notesNotInterested)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $notesNotInterested->notes }}</td>
                                @if(!auth()->user()->is_client)
                                <td>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <!-- Button to trigger the edit modal -->
                                                <button type="button" class="btn btn-primary ml-auto" data-toggle="modal"
                                                    data-target="#editModal{{ $notesNotInterested->id }}">
                                                    Edit
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <!-- Button to trigger the delete modal -->
                                                <button type="button" class="btn btn-danger ml-auto" data-toggle="modal"
                                                    data-target="#deleteModal{{ $notesNotInterested->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
@endif
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $notesNotInterested->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="editModalLabel{{ $notesNotInterested->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $notesNotInterested->id }}">
                                                    Edit Parent Stage</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Your edit form goes here, pre-filled with the existing data -->
                                                <form
                                                    action="{{ route('admin.notenotinterested.update', $notesNotInterested->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group row">
                                                        <label for="notes"
                                                            class="col-md-4 col-form-label text-md-right">Notes</label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="notes" id="notes" required
                                                                class="form-control"
                                                                value="{{ $notesNotInterested->notes }}">
                                                        </div>
                                                    </div>



                                                    <!-- Add other fields in a similar manner -->


                                            </div>
                                            <div class="modal-footer">
                                                <div class="form-group row">
                                                    <div class="col-md-12 text-center">
                                                        <button type="submit" class="btn btn-primary mt-3">Save
                                                            Changes</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-secondary mt-3"
                                                    data-dismiss="modal">Cancel</button>
                                            </div>
                                            </form>

                                        </div>

                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $notesNotInterested->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="deleteModalLabel{{ $notesNotInterested->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $notesNotInterested->id }}">
                                                    Delete Parent Stage</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body d-flex">
                                                <div>
                                                    <p>Are you sure you want to delete this parent stage?</p>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <form
                                                    action="{{ route('admin.notenotinterested.destroy', $notesNotInterested->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
