
@extends('layouts.admin')
@section('content')

    <h2>Application Purchased</h2>
    <div class="card">
        <div class="card-body">
<table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-applicationpurchased" id="followUpTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reference Number</th>
                        <th>Application No</th>
                        <th>Parent Name</th>
                        <th>Child name</th>
                        <th>Grade</th>
                        <th>Assigned to</th>
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
          @foreach ($applications->where('parent_stage_id',13 ) as $applicationpurchased)
          <td>{{ $counter++ }}</td>
          <td>
            @foreach ($lead as $leads)
                @if ($leads->id === $applicationpurchased->lead_id)
                    <a href="{{ route('admin.leads.show', ['lead' => $leads->id]) }}">
                        {{ $leads->ref_num }}
                    </a>
                @endif
            @endforeach
        </td>
        <td>
            {{ $applicationpurchased->application_no  ??  ''}}
        </td>
        <td>
            {{ $applicationpurchased->lead->name ?? '' }}
        </td>
        <td>
            {{ $applicationpurchased->lead->child_name ?? '' }}
        </td>
        <td>
            {{ $applicationpurchased->lead->grade_enquired ?? '' }}
        </td>
        <td>
            @if($applicationpurchased->for_whom)
                {{ \App\Models\User::find($applicationpurchased->for_whom)->representative_name ?? '' }}
            @else
                No User Assigned
            @endif
        </td>
                            <td>
                                {{ $applicationpurchased->follow_up_date }}
                            </td>
                            <td>
                                {{ $applicationpurchased->follow_up_time }}
                            </td>
                            <td>
                                {{ $applicationpurchased->notes }}
                            </td>
                            <td>
                                {{ $applicationpurchased->created_at->format('Y-m-d') }}
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
            // Existing JavaScript code for DataTable initialization

            // Additional customization for DataTable
            let table = $('.datatable-applicationpurchased').DataTable();
            table.on('draw.dt', function() {
                // Add any additional customization after the table is drawn
            });
        });
    </script>
@endsection
