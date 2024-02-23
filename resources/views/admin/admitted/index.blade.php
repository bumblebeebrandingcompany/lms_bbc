@extends('layouts.admin')
@section('content')
    <h2>Admitted Table</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-followups"
                id="followUpTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reference Number</th>
                        <th>Parent Name</th>
                        <th>Child name</th>
                        <th>Grade</th>
                        <th>Application No</th>
                        <th>Assigned By</th>
                        <th> Date</th>
                        <th>Time</th>
                        <th>Notes</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $counter = 1;
                    @endphp
                    @foreach ($admitteds as $admitted)
                        <td>{{ $counter++ }}</td>
                        <td>
                            {{ $admitted->lead->ref_num ?? '' }}
                        </td>
                        <td>
                            {{ $admitted->lead->name ?? '' }}
                        </td>
                        <td>
                            {{ $admitted->lead->child_name ?? 'Not Updated' }}
                        </td>
                        <td>
                            {{ $admitted->lead->grade_enquired ?? ''}}
                        </td>
                        <td>
                            {{ $admitted->application->application_no ?? '' }}
                        </td>
                        <td>
                            {{ $admitted->application->user->representative_name ?? 'No User Assigned' }}
                        </td>
                        <td>
                            {{ $admitted->follow_up_date ?? ''}}
                        </td>
                        <td>
                            {{ $admitted->follow_up_time ?? ''}}
                        </td>
                        <td>
                            {{ $admitted->notes ?? ''}}
                        </td>
                        <td>
                            {{ $admitted->created_at->format('Y-m-d') }}
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
