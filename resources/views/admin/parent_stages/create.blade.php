@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Create Stage</h2>
        <div class="card card-primary card-outline">
            <div class="card-body">
                {{-- Form --}}
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
                                <select name="selected_child_stages[]" id="selected_child_stages"
                                    class="form-control select2" multiple required
                                    data-minimum-results-for-search="Infinity">

                                    @foreach ($parentStages as $childStage)
                                        <option value="{{ $childStage->id }}">{{ $childStage->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <button class="btn btn-danger" type="submit">
                                    {{ trans('global.save') }}
                                </button>
                            </div>
                        </div>


                </form>

                {{-- Display all created stages in a table --}}
                <h3>All Created Stages</h3>
                <table class="table table-bordered table-striped table-hover datatable datatable-Project">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Parent Stage</th>
                            <th>Selected Child Stages</th>
                            <th>Actions</th>
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
                                <td>
                                    {{-- Add action links as needed --}}
                                    {{-- For example: --}}
                                    {{-- <a href="{{ route('admin.stages.edit', $stage->id) }}">Edit</a> --}}
                                    {{-- <form action="{{ route('admin.stages.destroy', $stage->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Delete</button>
                                </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
