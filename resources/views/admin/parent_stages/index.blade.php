@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="card">

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Create Parent Stage</h2>
                    <a href="{{ route('admin.stages.index') }}">
                        <button class="btn btn-primary ml-auto">Connect Stages</button>
                    </a>
                </div>
                <div class="spacer"></div>
                <div class="spacer"></div>
                <form method="post" action="{{ route('admin.parent-stages.store') }}">
                    @csrf
                    @if(!auth()->user()->is_client )
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" required class="form-control" placeholder="Enter a Stage....">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="name">Select a Tag</label>

                                <select name="tag_id" id="tag_id" class="form-control select" required data-minimum-results-for-search="Infinity">
                                    @foreach ($tags as $childStage)
                                        <option value="{{ $childStage->id }}">{{ $childStage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                <div class="spacer"></div>
                                <button type="submit" class="d-flex justify-content-between align-items-center btn btn-success ml-auto">Create Parent Stage</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
                <h2 class="mt-3">Parent Stages</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Tag Name</th>
                            @if(!auth()->user()->is_client )
                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($parentStages as $parentStage)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $parentStage->name }}</td>
                                <td>{{ $parentStage->tag->name }}</td>
                                @if(!auth()->user()->is_client )
                                <td>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <!-- Button to trigger the edit modal -->
                                                <button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#editModal{{ $parentStage->id }}">
                                                    Edit
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <!-- Button to trigger the delete modal -->
                                                <button type="button" class="btn btn-danger ml-auto" data-toggle="modal" data-target="#deleteModal{{ $parentStage->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
@endif
                                <!-- Edit Modal -->
                                <div class="modal fade" id="editModal{{ $parentStage->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $parentStage->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel{{ $parentStage->id }}">Edit Parent Stage</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Your edit form goes here, pre-filled with the existing data -->
                                                <form action="{{ route('admin.parent-stages.update', $parentStage->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="form-group row">
                                                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="name" id="name" required class="form-control" value="{{ $parentStage->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="tag_id" class="col-md-4 col-form-label text-md-right">Select a Tag</label>
                                                        <div class="col-md-8">
                                                            <select name="tag_id" id="tag_id" class="form-control" required>
                                                                @foreach ($tags as $childStage)
                                                                    <option value="{{ $childStage->id }}" {{ $parentStage->tag_id == $childStage->id ? 'selected' : '' }}>
                                                                        {{ $childStage->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Add other fields in a similar manner -->

                                                    <div class="form-group row">
                                                        <div class="col-md-12 text-center">
                                                            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $parentStage->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $parentStage->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $parentStage->id }}">Delete Parent Stage</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body d-flex">
                                                <div>
                                                    <p>Are you sure you want to delete this parent stage?</p>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.parent-stages.destroy', $parentStage->id) }}" method="POST">
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
