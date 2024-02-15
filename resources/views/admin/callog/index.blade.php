@extends('layouts.admin')
@section('content')
    <h1>Call Records</h1>
    <form action="{{ route('admin.callog.store') }}" method="post">
        @csrf
        <div class="card">
            @if(!auth()->user()->is_client)
            <div class="card-header">
                <h3 class="card-title">Call Records Table</h3>
                <button class="btn btn-primary float-right" type="submit">
                    Store Call Records</button>
            </div>
            @endif
    </form>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{-- <label for="date_range">Select a date</label>
                        <select class="form-control" id="date_range">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="tomorrow">Tomorrow</option>
                            <option value="this_week">This Week</option>
                            <option value="next_60_days" selected>Next 60 Days</option>
                            <option value="next_30_days">Next 30 Days</option>
                            <option value="last_week">Last Week</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="last_60_days">Last 60 Days</option>
                            <option value="last_year">Last One Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="custom_range_container" style ="display:none;">
                    <div class="form-group">
                        <label for="custom_range">Custom Range</label>
                        <input class="form-control" type="text" name="custom_range" id="custom_range"
                            value="{{ old('custom_range') }}">
                    </div>
                </div> --}}
                    <div class="col-md-4">
                        <form method="get" action="{{ url()->current() }}">
                            <label for="recordsPerPage">Records Per Page:</label>
                            <select class="form-control ml-2 select2" id="recordsPerPage" name="perPage"
                                onchange="this.form.submit()">
                                <option value="10" {{ request('perPage', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="50" {{ request('perPage', 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('perPage', 10) == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('perPage', 10) == 200 ? 'selected' : '' }}>200</option>
                            </select>
                        </form>
                    </div>
                </div>

                <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-callog">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ref num</th>
                            <th>Client number</th>
                            <th>Status</th>
                            <th>Call Duration</th>
                            <th>Called On</th>
                            <th>Call Start Time</th>
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
                                <td>{{ $callRecord->lead->ref_num ?? ''}} </td>
                                <td>{{ $callRecord->client_number }}</td>
                                <td>{{ $callRecord->status }}</td>
                                <td>
                                    <?php
                                    $answeredSeconds = $callRecord->call_duration;
                                    $hours = floor($answeredSeconds / 3600);
                                    $minutes = floor(($answeredSeconds % 3600) / 60);
                                    $seconds = $answeredSeconds % 60;
                                    ?>
                                    {{ $hours ? $hours . 'h ' : '' }}
                                    {{ $minutes ? $minutes . 'm ' : '' }}
                                    {{ $seconds . 's' }}
                                </td>
                                <td>{{ $callRecord->called_on }}</td>
                                @php
                                    $callOnTime = $callRecord->call_on_time; // Replace this with your actual time
                                    $formattedTime = \Carbon\Carbon::createFromFormat('H:i:s', $callOnTime)->format('h:i A');
                                @endphp

                                <td>{{ $formattedTime }}</td>
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
                <!-- Pagination Links -->
                <div class="d-flex justify-content-end">
                    {{ $callRecords->links('pagination::bootstrap-4') }}
                </div>

                <!-- Records Per Page Dropdown -->
            @endsection


            @section('scripts')
                @parent
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
                <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
                <link rel="stylesheet" type="text/css"
                    href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

                <script>
                    $(document).ready(function() {
                        // Initialize the date range picker for Site Visit
                        $('#custom_range').daterangepicker();

                        // Show/hide the custom range container based on the selected option for Site Visit
                        $('#date_range').change(function() {
                            var selectedOption = $(this).val();
                            if (selectedOption === 'custom') {
                                $('#custom_range_container').show();
                            } else {
                                $('#custom_range_container').hide();
                                filterTable(selectedOption, 'siteVisit');
                            }
                        });

                        // Handle filtering when the custom range is selected for Site Visit
                        $('#custom_range').change(function() {
                            var customRange = $(this).val();
                            filterTable('custom', 'siteVisit', customRange);
                        });

                        // Initialize the date range picker for Reschedule
                        $('#custom_range_reschedule').daterangepicker();

                        // Show/hide the custom range container based on the selected option for Reschedule
                        $('#date_range_reschedule').change(function() {
                            var selectedOption = $(this).val();
                            if (selectedOption === 'custom') {
                                $('#custom_range_container_reschedule').show();
                            } else {
                                $('#custom_range_container_reschedule').hide();
                                filterTable(selectedOption, 'reschedule');
                            }
                        });

                        // Handle filtering when the custom range is selected for Reschedule
                        $('#custom_range_reschedule').change(function() {
                            var customRange = $(this).val();
                            filterTable('custom', 'reschedule', customRange);
                        });

                        function filterTable(selectedOption, tableType, customRange = null) {
                            var startDate, endDate;
                            var tableId;

                            if (tableType === 'siteVisit') {
                                startDate = moment().startOf('day');
                                endDate = moment().endOf('day');
                                tableId = '#siteVisitTable';
                            } else if (tableType === 'reschedule') {
                                // Adjust the logic for reschedule table if needed
                                startDate = moment().startOf('day');
                                endDate = moment().endOf('day');
                                tableId = '#rescheduleTable';
                            }

                            switch (selectedOption) {
                                // ... (Your existing switch cases for date range options) ...
                            }

                            // Filter the table rows based on the calculated start and end dates
                            $(tableId + ' tbody tr').hide().filter(function() {
                                var createdDate = $(this).data('created-at');
                                return moment(createdDate, 'YYYY-MM-DD').isBetween(startDate, endDate, null, '[]');
                            }).show();
                        }
                    });
                </script>
            @endsection
