<h2 class="card-title"> Lead ID: {{ $lead->id }}</h2>
<br>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<h1>
    Site Visit</h1>
<div class="card">

    <div class="table-responsive">
        <div class="col-md-1">

            <table class="table table-bordered table-striped" id="siteVisiticon">

                <tr>
                    <td>Conducted

                        <div class="float-right"
                            style="background-color: green; padding: 2px; display: inline-block; border-radius: 5px;"title="Conducted">
                            <i class="far fa fa-check nav-icon"></i>
                        </div>
                    </td>

                    <td>NotVisited

                        <div class="float-right"
                            style="background-color: rgb(119, 84, 214); padding: 2px; display: inline-block; border-radius: 5px;"title="NotVisited">
                            <i class="fa fa-eye-slash" style="font-size:16px"></i>
                        </div>
                    </td>

                    <td>Rescheduled

                        <div class="float-right"
                            style="background-color: rgb(236, 47, 220); padding: 2px; display: inline-block; border-radius: 5px;"title="Rescheduled">
                            <i class="fas fa-check-double" style="font-size:18px"></i>
                        </div>
                    </td>

                    <td>Cancelled
                        <div class="float-right"
                            style="background-color: rgb(240, 18, 18); padding: 2px; display: inline-block; border-radius: 5px;"title="Cancelled">
                            <i class="fa fa-close" style="font-size:20px"></i>
                        </div>
                    </td>

                    <td>Scheduled
                        <div class="float-right"
                            style="background-color: rgb(47, 230, 236); padding: 2px; display: inline-block; border-radius: 5px;"title="Scheduled">
                            <i class="fas fa-calendar-check nav-icon"></i>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Ref Number</th>
                    <th>Campaign Name</th>
                    <th>Site Visit Date</th>
                    <th>Site Visit Time</th>

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
                            {{ $sitevisit->notes }}
                            <td style="text-align: center;">
                                @if ($sitevisit->parent_stage_id == 10)
                                @if (!auth()->user()->is_client && !auth()->user()->is_frontoffice)
                                <div
                                            style="background-color: rgb(47, 230, 236); padding: 5px; display: inline-block; border-radius: 5px;"title="Scheduled">
                                            <i class="fas fa-calendar-check nav-icon"></i>
                                        </div>
                                    @endif
                                @endif
                                <div class="btn-group" role="group" aria-label="Site Visit Actions">
                                    <form action="{{ route('admin.sitevisits.reschedule', $sitevisit->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @if ($sitevisit->parent_stage_id == 19)
                                            @if (!auth()->user()->is_superadmin)
                                                <div
                                                    style="background-color: rgb(236, 47, 220); padding: 5px; display: inline-block; border-radius: 5px;"title="Resceduled">
                                                    <i class="fas fa-check-double" style="font-size:17px"></i>
                                                </div>
                                            @else
                                                <div
                                                    style="background-color: rgb(236, 47, 220); padding: 5px; display: inline-block; border-radius: 5px;"title="Rescheduled">
                                                    <i class="fas fa-check-double" style="font-size:17px"></i>
                                                </div>
                                            @endif
                                        @elseif (!auth()->user()->is_client && $sitevisit->lead && in_array($sitevisit->parent_stage_id, [11, 20, 27, 10,13]))

                                        @elseif (!auth()->user()->is_client &&!auth()->user()->is_frontoffice && $sitevisit->lead && $sitevisit->parent_stage_id != 20)
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#editModal{{ $sitevisit->id }}">
                                                Reschedule
                                            </button>
                                        @endif
                                        <div class="modal fade" id="editModal{{ $sitevisit->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="editModalLabel{{ $sitevisit->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $sitevisit->id }}">
                                                            Reschedule</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" name="lead_id" value="{{ $sitevisit->lead_id }}">
                                                        <h3 class="card-title">Lead Id : {{ $sitevisit->lead_id }}</h3>
                                                        <div class="form-group">
                                                            <input type="hidden" name="parent_stage_id" value="10">
                                                            @if ($errors->has('client'))
                                                                <span class="text-danger">{{ $errors->first('client') }}</span>
                                                            @endif
                                                            <span class="help-block">{{ trans('cruds.project.fields.client_helper') }}</span>
                                                        </div>
                                                        <div class="float-left">
                                                            <label for="Date">Select Date</label>
                                                        </div>
                                                        <input type="date" name="follow_up_date" class="form-control datepicker"
                                                            value="{{ $sitevisit->follow_up_date }}">
                                                        <div class="float-left">
                                                            <label for="Time">Select Time</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="Time">select time </label>
                                                            <input type="time"
                                                                class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                                                name="follow_up_time" id="follow_up_time" rows="3"
                                                                required>{{ old('follow_up_time') }}
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="float-left">
                                                                <label for="followUpContent">Notes</label>
                                                            </div>
                                                            <textarea id="followUpContent" name="notes" class="form-control" rows="5">{{ $sitevisit->notes }}</textarea>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="mr-2"></div>
                                <form action="{{ route('admin.sitevisits.conducted', $sitevisit->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    @if ($sitevisit->parent_stage_id == 11)
                                        @if (!auth()->user()->is_superadmin)
                                            <div style="background-color: green; padding: 5px; display: inline-block; border-radius: 5px;"
                                                title="Conducted">
                                                <i class="far fa fa-check nav-icon"></i>
                                            </div>
                                        @else
                                            <div
                                                style="background-color: green; padding: 5px; display: inline-block; border-radius: 5px;"title="Conducted">
                                                <i class="far fa fa-check nav-icon"></i>
                                            </div>
                                        @endif
                                    @elseif (!auth()->user()->is_superadmin &&  $sitevisit->lead && in_array($sitevisit->parent_stage_id, [26, 27, 20, 19,13]))

                                    @elseif (!auth()->user()->is_superadmin && !auth()->user()->is_presales && $sitevisit->lead && $sitevisit->parent_stage_id != 11)
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
                                                    <br>
                                                    <br>
                                                    {{-- <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                                        name="user_id" id="user_id" required>
                                                        @foreach ($client as $id => $clients)
                                                            @foreach ($clients->clientUsers as $user)
                                                                @if ($user->user_type == 'Admissionteam')
                                                                    <option value="{{ $user->id }}"
                                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->representative_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select> --}}
                                                    <br>


                                                    <div class="form-group">
                                                        <label class=float-left for="noteContent">Note Content</label>
                                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                            rows="4" required>{{ old('notes') }}</textarea>
                                                    </div>
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
                                <form action="{{ route('admin.sitevisits.applicationpurchased', $sitevisit->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    @if ($sitevisit->parent_stage_id == 13)
                                        @if (!auth()->user()->is_superadmin)
                                            <div style="background-color: rgb(235, 202, 19); padding: 5px; display: inline-block; border-radius: 5px;"
                                                title="Conducted">
                                                <i class="	fas fa-receipt nav-icon"></i>
                                            </div>
                                        @else
                                            <div
                                                style="background-color: rgb(235, 202, 19); padding: 5px; display: inline-block; border-radius: 5px;"title="Conducted">
                                                <i class="	fas fa-receipt nav-icon"></i>
                                            </div>
                                        @endif
                                    @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && in_array($sitevisit->parent_stage_id, [26, 27, 20, 19]))

                                    @elseif (!auth()->user()->is_superadmin && !auth()->user()->is_presales && $sitevisit->lead && $sitevisit->parent_stage_id != 13)
                                        @if ($sitevisit->parent_stage_id != 12)
                                            <!-- Add this condition to exclude Not Visited -->
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#applicationpurchasedmodel{{ $sitevisit->id }}">
                                                Application Purchased </button>
                                        @endif
                                    @endif
                                    <!-- Modal for Conducted -->
                                    <div class="modal fade" id="applicationpurchasedmodel{{ $sitevisit->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="applicationpurchasedLabel{{ $sitevisit->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="applicationpurchasedLabel{{ $sitevisit->id }}">Confirmation</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure this site visit is Application purchased?
                                                    <br>
                                                    <br>
                                                    <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                                        name="user_id" id="user_id" required>
                                                        @foreach ($client as $id => $clients)
                                                            @foreach ($clients->clientUsers as $user)
                                                                @if ($user->user_type == 'Admissionteam')
                                                                    <option value="{{ $user->id }}"
                                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->representative_name }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                    <br>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="parent_stage_id" value="13">
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
                                            <div
                                                style="background-color: rgb(240, 18, 18); padding: 5px; display: inline-block; border-radius: 5px;"title="Cancelled">
                                                <i class="fa fa-close" style="font-size:18px"></i>
                                            </div>
                                </div>
                                </div>
                            @elseif ($sitevisit->parent_stage_id != 11 && $sitevisit->parent_stage_id != 10 && $sitevisit->parent_stage_id != 19&& $sitevisit->parent_stage_id != 13)
                                <!-- Add this condition to hide "Cancel" button if the stage is conducted or 10 -->
                                @php
                                    $canCancel = !auth()->user()->is_client && !auth()->user()->is_frontoffice && $sitevisit->lead && $sitevisit->parent_stage_id != 20;
                                @endphp
                                @if ($canCancel)
                                    <br>
                                    <button type="button" class="btn btn-sm btn-danger" style="width:90px" data-toggle="modal"
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
                                                <h5 class="modal-title" id="cancelModalLabel{{ $sitevisit->id }}">Confirmation
                                                </h5>
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
                                        @if (!auth()->user()->is_superadmin && !auth()->user()->is_presales)
                                            <div class=float-center>
                                                <div style="background-color: rgb(119, 84, 214); padding: 5px; display: inline-block; border-radius: 5px;"
                                                    title="Not Visited">
                                                    <i class="fa fa-eye-slash" style="font-size:18px"></i>
                                                </div>
                                            @else
                                                <br>
                                                <div
                                                    style="background-color: rgb(119, 84, 214); padding: 5px; display: inline-block; border-radius: 5px;"title="Not Visited">
                                                    <i class="fa fa-eye-slash" style="font-size:18px"></i>
                                                </div>
                                        @endif
                                        </div>
                                    @elseif (!auth()->user()->is_superadmin && $sitevisit->lead && in_array($sitevisit->parent_stage_id, [11, 26, 27, 20, 19,13]))

                                    @elseif (!auth()->user()->is_superadmin && !auth()->user()->is_presales && $sitevisit->lead && $sitevisit->parent_stage_id != 12)
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
                            <td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
