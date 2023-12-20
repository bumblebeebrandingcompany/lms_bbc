<h1>
    Site Visit</h1>
<div class="card">


    <table class="table">
        <thead>
            <tr>
                <th>Reference Number</th>
                <th>Campaign Name</th>
                <th>Site Visit Date</th>
                <th>Site Visit Time</th>
                <th>Site Visit By</th>
                <th>Notes</th>
                {{-- <th>Stage Id</th> --}}
                <th>Action</th>
                <!-- Add more table headers for other lead follow-up properties -->
            </tr>
        </thead>
        <tbody>
            @foreach ($sitevisit as $sitevisit)
                <tr>
                    <td>

                        {{ $sitevisit->lead_id }}
                    </td>
                    <td>{{ $lead->campaign->campaign_name }}</td>

                    <td>{{ $sitevisit->follow_up_date }}</td>


                    <td>{{ $sitevisit->follow_up_time }}</td>

                    <td>
                        {{ $sitevisit->users->representative_name }}
                    </td>
                    <td>
                        {{ $sitevisit->notes }}
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
