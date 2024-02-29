@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Create Stage</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                {{-- Form --}}
                @if(!auth()->user()->is_client )
                <form method="POST" action="{{ route('admin.stages.store') }}" enctype="multipart/form-data"
                    class="my-custom-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="stage_id">Parent Stage:</label>
                                <select name="stage_id" id="stage_id" class="form-control" required>
                                    @foreach ($parentStages as $parentStage)
                                        <option value="{{ $parentStage->id }}">{{ $parentStage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="selected_child_stages">Select Child Stages:</label>
                                <br>
                                <select multiple name="selected_child_stages[]" id="selected_child_stages"
                                    class="form-control select2" required>
                                    @foreach ($parentStages as $childStage)
                                        <option value="{{ $childStage->id }}">{{ $childStage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="selected_child_stages"></label>
                                <br>
                                <div class="form-group">
                                    <button class="btn btn-danger float-right" type="submit">
                                        {{ trans('global.save') }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                @endif

                {{-- Display all created stages in a table --}}
                <h3>All Created Stages</h3>
                <table class="table table-bordered table-striped table-hover datatable datatable-Project">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Parent Stage</th>
                            <th>Selected Child Stages</th>
                            @if(!auth()->user()->is_client )

                            <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($stages as $stage)
                            <tr>
                                <td>{{ $counter++ }}</td>
                                <td>{{ $stage->parentStage->name }}</td>
                                <td>
                                    @foreach (json_decode($stage->selected_child_stages) as $childStageId)
                                        {{ $parentStages->firstWhere('id', $childStageId)->name }},
                                    @endforeach
                                </td>
                                @if(!auth()->user()->is_client )
                                <td>
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#editStageModal{{ $stage->id }}">Edit</button>
                                    <!-- Delete Button -->
                                    <button class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#deleteStageModal{{ $stage->id }}">Delete</button>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals for Edit and Delete -->
    @foreach ($stages as $stage)
        <!-- Edit Modal -->
        <div class="modal fade" id="editStageModal{{ $stage->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editStageModalLabel{{ $stage->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStageModalLabel{{ $stage->id }}">Edit Stage</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- Your edit form goes here --}}
                        <form method="POST" action="{{ route('admin.stages.update', $stage->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- Add your form fields for editing here -->
                            <div class="form-group">
                                <label for="stage_id">Edited Parent Stage:</label>
                                <select name="stage_id" id="stage_id" class="form-control" required>
                                    @foreach ($parentStages as $parentStage)
                                        <option value="{{ $parentStage->id }}"
                                            {{ $parentStage->id == $stage->stage_id ? 'selected' : '' }}>
                                            {{ $parentStage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Add other fields as needed -->
                            <div class="form-group">
                                <label for="selected_child_stages">Edited Child Stages:</label>
                                <select name="selected_child_stages[]" id="selected_child_stages"
                                    class="form-control select2" multiple required
                                    data-minimum-results-for-search="Infinity">

                                    @foreach ($parentStages as $childStage)
                                        <option value="{{ $childStage->id }}"
                                            {{ in_array($childStage->id, json_decode($stage->selected_child_stages)) ? 'selected' : '' }}>
                                            {{ $childStage->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteStageModal{{ $stage->id }}" tabindex="-1" role="dialog"
            aria-labelledby="deleteStageModalLabel{{ $stage->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteStageModalLabel{{ $stage->id }}">Delete Stage</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this stage?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="{{ route('admin.stages.destroy', $stage->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
