@extends('layouts.admin')

@section('content')
    <h3>Site Visit And Reschedule</h3>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lead Site Visit Table</h3>
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
                            <option value="last_60_days">Last 60 Days</option>
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
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-sitevisit"
                id="siteVisitTable">
                <thead>
                    <tr>
                        <th>Reference Number</th>
                        <th>Parent Name</th>
                        <th>Campaign Name</th>
                        <th>Site Visit Date</th>
                        <th>Site Visit Time</th>
                        <th>Supervise By</th>
                        <th>Notes</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sitevisits as $sitevisit)
                        <tr data-created-at="{{ $sitevisit->follow_up_date }}">
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $leads->ref_num }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $leads->name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $leads->campaign->campaign_name }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $sitevisit->follow_up_date }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $sitevisit->follow_up_time }}
                            </td>
                            <td>
                                {{ $sitevisit->users->representative_name }}
                            </td>
                            <td>
                                @foreach ($lead as $leads)
                                    @if ($leads->id === $sitevisit->lead_id)
                                        {{ $sitevisit->notes }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $sitevisit->created_at->format('Y-m-d') }}
                            </td>
                            <td>
                                @if ($sitevisit->parent_stage_id == 10)
                                @if (!auth()->user()->is_client)
                                <span class="badge badge-info">Scheduled</span>
                                @endif
                                @endif
                                <div class="btn-group" role="group" aria-label="Site Visit Actions">
                                    <form action="{{ route('admin.sitevisits.reschedule', $sitevisit->id) }}"
                                        method="POST">
                                        @csrf

                                        @method('PUT')
                                        @if ($sitevisit->parent_stage_id == 19)
                                            @if (!auth()->user()->is_superadmin)
                                                <span class="badge badge-info">Rescheduled</span>
                                            @else
                                                <span class="badge badge-info">Rescheduled</span>
                                            @endif
                                        @elseif (!auth()->user()->is_client && $sitevisit->lead && in_array($sitevisit->parent_stage_id, [11, 20, 27, 10]))

                                        @elseif (!auth()->user()->is_client && $sitevisit->lead && $sitevisit->parent_stage_id != 20)
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#editModal{{ $sitevisit->id }}">
                                                Reschedule
                                            </button>
                                        @endif
                                        <div class="modal fade" id="editModal{{ $sitevisit->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="editModalLabel{{ $sitevisit->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $sitevisit->id }}">
                                                            Reschedule</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <div class="modal-body">

                                                        <input type="hidden" name="lead_id"
                                                            value="{{ $sitevisit->lead_id }}">
                                                        <h3 class="card-title">Lead Id : {{ $sitevisit->lead_id }}</h3>

                                                        <div class="form-group">
                                                            <label class="required"
                                                                for="user_id">{{ trans('cruds.project.fields.client') }}</label>
                                                            <select
                                                                class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                                                name="user_id" id="user_id" required>
                                                                @foreach ($client as $id => $clients)
                                                                    @foreach ($clients->clientUsers as $user)
                                                                        <option value="{{ $user->id }}"
                                                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->representative_name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                            <input type="hidden" name="parent_stage_id" value="10">
                                                            @if ($errors->has('client'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('client') }}</span>
                                                            @endif
                                                            <span
                                                                class="help-block">{{ trans('cruds.project.fields.client_helper') }}</span>
                                                        </div>

                                                        <label for="Date">Select Date</label>
                                                        <input type="date" name="follow_up_date"
                                                            class="form-control datepicker"
                                                            value="{{ $sitevisit->follow_up_date }}">

                                                        <label for="Time">Select Time</label>
                                                        <input id="follow_up_time" name="follow_up_time" type="text"
                                                            class="form-control timepicker"
                                                            value="{{ $sitevisit->follow_up_time }}">

                                                        <div class="form-group">
                                                            <label for="followUpContent">Notes</label>
                                                            <textarea id="followUpContent" name="notes" class="form-control" rows="5">{{ $sitevisit->notes }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Cancel</button>
                                                    </div>
                                    </form>
                                </div>
        </div>
    </div>

    <div class="mr-2"></div>
    <form action="{{ route('admin.sitevisits.conducted', $sitevisit->id) }}" method="POST">
        @csrf
        @method('PUT')

        @if ($sitevisit->parent_stage_id == 11)
            @if (!auth()->user()->is_superadmin)
                <span class="badge badge-success">Conducted</span>
            @else
                <span class="badge badge-success">Conducted</span>
            @endif
        @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && in_array($sitevisit->parent_stage_id, [26, 27, 20, 19]))

        @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && $sitevisit->parent_stage_id != 11)
            @if ($sitevisit->parent_stage_id != 12)
                <!-- Add this condition to exclude Not Visited -->
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                    data-target="#conductedModel{{ $sitevisit->id }}">
                    Conducted
                </button>
            @endif
        @endif

        <!-- Modal for Conducted -->
        <div class="modal fade" id="conductedModel{{ $sitevisit->id }}" tabindex="-1" role="dialog"
            aria-labelledby="conductedModelLabel{{ $sitevisit->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="conductedModelLabel{{ $sitevisit->id }}">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        Are you sure this site visit is Conducted?
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="parent_stage_id" value="11">
                        <button type="submit" class="btn btn-danger">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>



    <div class="mr-2"></div>





    <!-- Add margin to create space -->
    <div class="mr-2"></div>
    <div class="btn-group" role="group" aria-label="Site Visit Actions">
        <form method="POST" action="{{ route('admin.sitevisits.cancel', $sitevisit->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($sitevisit->parent_stage_id == 20)
                <span class="badge badge-danger">Cancelled</span>
            @elseif ($sitevisit->parent_stage_id != 11 && $sitevisit->parent_stage_id != 10 && $sitevisit->parent_stage_id != 19)
                <!-- Add this condition to hide "Cancel" button if the stage is conducted or 10 -->
                @php
                    $canCancel = !auth()->user()->is_client && $sitevisit->lead && $sitevisit->parent_stage_id != 20;
                @endphp

                @if ($canCancel)
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                        data-target="#cancelModal{{ $sitevisit->id }}">
                        Cancel
                    </button>
                @endif

                <!-- Modal -->
                <div class="modal fade" id="cancelModal{{ $sitevisit->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="cancelModalLabel{{ $sitevisit->id }}" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="cancelModalLabel{{ $sitevisit->id }}">Confirmation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <input type="hidden" name="parent_stage_id" value="20">

                            <div class="modal-body">
                                Are you sure you want to cancel?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Confirm</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>

    <div class="mr-2"></div>

    <form action="{{ route('admin.sitevisits.notvisited', $sitevisit->id) }}" method="POST">
        @csrf
        @method('PUT')

        @if ($sitevisit->parent_stage_id == 12)
            @if (!auth()->user()->is_superadmin)
                <span class="badge badge-warning">Not Visited</span>
            @else
                <span class="badge badge-warning">Not Visited</span>
            @endif
        @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && in_array($sitevisit->parent_stage_id, [11, 26, 27, 20, 19]))

        @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && $sitevisit->parent_stage_id != 12)
            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                data-target="#notVisitedModel{{ $sitevisit->id }}">
                Not Visited
            </button>
        @endif


        <!-- Modal -->
        <div class="modal fade" id="notVisitedModel{{ $sitevisit->id }}" tabindex="-1" role="dialog"
            aria-labelledby="notVisitedModelLabel{{ $sitevisit->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="notVisitedModelLabel{{ $sitevisit->id }}">Confirmation
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        Are you sure this site visit is not visited?
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="parent_stage_id" value="12">
                        <button type="submit" class="btn btn-danger">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>





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

            function filterTable(selectedOption, customRange = null) {
                var startDate, endDate;

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
                $('#siteVisitTable tbody tr').hide().filter(function() {
                    var createdDate = $(this).data('created-at');
                    return moment(createdDate, 'YYYY-MM-DD').isBetween(startDate, endDate, null, '[]');
                }).show();
            }
        });
    </script>
@endsection
