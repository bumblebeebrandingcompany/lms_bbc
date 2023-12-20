@extends('layouts.admin')
@section('content')
    <h1>Call Records</h1>
    <form action="{{ route('admin.callog.store') }}" method="post">
        @csrf
        <button class="btn btn-primary" type="submit">
            Store Call Records</button>
    </form>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Call Records Table</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date_range">Select a date</label>
                        <select class="form-control" id="date_range">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="tomorrow">Tomorrow</option>
                            <option value="this_week">This Week</option>
                            <option value="next_60_days" selected>Next 60 Days</option>
                            <option value="next_30_days">Next 30 Days</option>
                            <option value="last_week">Last Week</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="last_60_days" >Last 60 Days</option>
                            <option value="last_year">Last One Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="custom_range_container" style="display:none;">
                    <div class="form-group">
                        <label for="custom_range">Custom Range</label>
                        <input class="form-control" type="text" name="custom_range" id="custom_range"
                            value="{{ old('custom_range') }}">
                    </div>
                </div>
            </div>
    <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-callog">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ref num</th>
                <th>Client number</th>
                <th>Status</th>
                <th>Call Start Time</th>
                <th>Call Duration</th>
                <th>Called On</th>
                <th>Call Recording</th>

            </tr>
        </thead>
        <tbody>
            @php
            $counter = 1;
            @endphp
            @foreach ($callRecords as $callRecord)
                <tr>
                    <td>{{ $counter++ }}</td>
                    <td>{{ $callRecord->lead->ref_num }} </td>
                    <td>{{ $callRecord->client_number }}</td>
                    <td>{{ $callRecord->status }}</td>
                    <td>{{ $callRecord->called_on }}</td>
                    <td>{{ $callRecord->call_start_time }}</td>
                    <td>
                        <?php
                        $answeredSeconds = $callRecord->call_duration;
                        $hours = floor($answeredSeconds / 3600);
                        $minutes = floor(($answeredSeconds % 3600) / 60);
                        $seconds = $answeredSeconds % 60;
                        ?>
                        {{ $hours ? $hours.'h ' : '' }}
                        {{ $minutes ? $minutes.'m ' : '' }}
                        {{ $seconds.'s' }}
                    </td>

                    <td>
                            <audio controls>
                                <source src="{{ asset($callRecord['call_recordings']) }}" type="audio/mp3">
                                Your browser does not support the audio tag.
                            </audio>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@endsection



