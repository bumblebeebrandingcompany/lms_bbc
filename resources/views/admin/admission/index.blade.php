@extends('layouts.admin')
@section('content')
    <h2>Admission Table</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Admission"
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
                    @foreach ($admissions as $admission)
                        <td>{{ $counter++ }}</td>

                        <td>
                            @foreach ($lead as $leads)
                                @if ($leads->id === $admission->lead_id)
                                    <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                                        {{ $leads->ref_num }}
                                    </a>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            {{ $admission->lead->name ?? '' }}
                        </td>
                        <td>
                            {{ $admission->lead->child_name ?? 'Not Updated' }}
                        </td>
                        <td>
                            {{ $admission->lead->grade_enquired ?? ''}}
                        </td>
                        <td>
                            {{ $admission->application->application_no ?? '' }}
                        </td>
                        <td>
                            {{ $admission->application->user->representative_name ?? 'No User Assigned' }}
                        </td>
                        <td>
                            {{ $admission->follow_up_date ?? ''}}
                        </td>
                        <td>
                            {{ $admission->follow_up_time ?? ''}}
                        </td>
                        <td>
                            {{ $admission->notes ?? ''}}
                        </td>
                        <td>
                            {{ $admission->created_at->format('Y-m-d') }}
                        </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let table = $('.datatable-Admission').DataTable();
            table.on('draw.dt', function() {
            });
        });
    </script>
@endsection
