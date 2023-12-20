@extends('layouts.admin')
@section('content')
    <h1>Lead Follow Up List</h1>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lead Follow-Up Table</h3>
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
                            <option value="last_60_days" selected>Last 60 Days</option>
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

            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-followup" id="followUpTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reference Number</th>
                        <th>Parent Name</th>
                        <th>Campaign Name</th>
                        <th>Follow-Up Date</th>
                        <th>Follow-Up Time</th>
                        <th>Follow-Up By</th>
                        <th>Notes</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $counter = 1;
                @endphp
                    @foreach ($followUps as $followUp)

                        <tr data-created-at="{{ $followUp->follow_up_date}}">
                            <td>{{ $counter++ }}</td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $leads->ref_num }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $leads->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $leads->campaign->campaign_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $followUp->follow_up_date }}
                            </td>
                            <td>
                                {{ $followUp->follow_up_time }}
                            </td>
                            <td>
                                {{ $followUp->users->representative_name }}
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $followUp->lead_id)
                                        {{ $followUp->notes }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $followUp->created_at->format('Y-m-d') }}
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(document).ready(function() {
            // Initialize the date range picker
            $('#custom_range').daterangepicker();

            // Show/hide the custom range container based on the selected option
            $('#date_range').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === 'custom') {
                    $('#custom_range_container').show();
                } else {
                    $('#custom_range_container').hide();
                    filterTable(selectedOption);
                }
            });

            // Handle filtering when the custom range is selected
            $('#custom_range').change(function() {
                var customRange = $(this).val();
                filterTable('custom', customRange);
            });

            // Function to filter the table based on the selected date range
            function filterTable(selectedOption, customRange = null) {
                var startDate, endDate;

                // Logic to determine the start and end dates based on the selected option
                switch (selectedOption) {
                    case 'today':
                        startDate = moment().startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'yesterday':
                        startDate = moment().subtract(1, 'days').startOf('day');
                        endDate = moment().subtract(1, 'days').endOf('day');
                        break;
                    case 'last_30_days':
                        startDate = moment().subtract(29, 'days').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'last_60_days':
                        startDate = moment().subtract(59, 'days').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'next_60_days':
                        startDate = moment().startOf('day');
                        endDate = moment().add(59, 'days').startOf('day');

                        break;
                    case 'last_year':
                        startDate = moment().subtract(1, 'year').startOf('day');
                        endDate = moment().endOf('day');
                        break;
                    case 'last_week':
                        startDate = moment().subtract(1, 'week').startOf('week');
                        endDate = moment().subtract(1, 'week').endOf('week');
                        break;
                    case 'next_30_days':
                        startDate = moment().startOf('day');
                        endDate = moment().add(29, 'days').endOf('day');
                        break;
                    case 'this_week':
                        startDate = moment().add(1, 'week').startOf('week');
                        endDate = moment().add(1, 'week').endOf('week');
                        break;
                    case 'tomorrow':
                        startDate = moment().add(1, 'day').startOf('day');
                        endDate = moment().add(1, 'day').endOf('day');
                        break;
                    case 'custom':
                        if (customRange) {
                            var dates = customRange.split(' - ');
                            startDate = moment(dates[0], 'YYYY-MM-DD').startOf('day');
                            endDate = moment(dates[1], 'YYYY-MM-DD').endOf('day');
                        }
                        break;
                }

                // Filter the table rows based on the calculated start and end dates
                $('#followUpTable tbody tr').hide().filter(function() {
                    var createdDate = $(this).data('created-at');
                    return moment(createdDate, 'YYYY-MM-DD').isBetween(startDate, endDate, null, '[]');
                }).show();
            }
        });
    </script>
@endsection
