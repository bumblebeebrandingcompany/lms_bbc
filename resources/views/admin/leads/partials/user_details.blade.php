<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://unpkg.com/pdfmake@0.1.18/build/pdfmake.min.js"></script>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stages Popup</title>
<style>
    .popup {
        display: none;
        position: fixed;
        top: 30%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        width: 400px;
        /* Adjust the width as needed */
        height: 300px;
        /* Adjust the height as needed */
    }
</style>
<style>
    .popup {
        display: none;
    }

    .show {
        display: block;
    }
</style>
<style>
    .edit-field.error {
        background-color: #ffcccc;
        /* Change this to the desired shade of red */
    }
</style>
<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div style="text-align: right;">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            {{-- <button type="submit" class="btn btn-primary" id="initiateCallButton">
                    <i class="fas fa-phone fa-flip-horizontal"></i>
                </button> --}}
            @if (!auth()->user()->is_client)
                <div class="lead-item">
                    <a href="{{ route('admin.leads.initiateCall', [$lead->id]) }}" class="btn btn-primary"> <i
                            class="fas fa-phone fa-flip-horizontal"></i></a>
                </div>
            @endif
        </div>
        <br>
        @if (!auth()->user()->is_client)
            <button type="submit" class="float-right edit-button btn-primary">Edit</button>
        @endif
        <div class="text-center">
            @php
                $avatar = 'https://ui-avatars.com/api/?background=random&font-size=0.7&name=' . $lead->name;
            @endphp
            <img class="profile-user-img img-fluid img-circle" src="{{ $avatar }}" alt="{{ $lead->name ?? '' }}">
        </div>

        <button type="submit" class="float-right save-button btn-success" style="display:none;">Save</button>
        <h3 class="profile-username text-center">
            {{ $lead->name ?? '' }}
        </h3>
        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>{{ trans('messages.ref_num') }}</b>
                <a class="float-right">{{ $lead->ref_num }}</a>
            </li>
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_lead_id')</b>
                <a class="float-right">
                    {!! $lead->sell_do_lead_id ?? '' !!}
                </a>
                </li> -->
            <li class="list-group-item">
                <b> @lang('messages.email')</b>
                <a class="float-right">
                    @if (auth()->user()->is_channel_partner_manager && !empty($lead->email))
                        {{ maskEmail($lead->email) }}
                    @else
                        {{ $lead->email ?? '' }}
                    @endif
                </a>
            </li>
            {{-- <li class="list-group-item">
                <b>@lang('messages.additional_email_key')</b>
                <a class="float-right">
                    @if (auth()->user()->is_channel_partner_manager && !empty($lead->additional_email))
                        {{ maskEmail($lead->additional_email) }}
                    @else
                        {{ $lead->additional_email ?? '' }}
                    @endif
                </a>
                </li> --}}
            <li class="list-group-item">
                <b>{{ trans('messages.additional_email_key') }}</b>
                <a class="float-right">

                    <span class="value-container">
                        @if (!$errors->has('additional_email'))
                            <span class="display-value">
                                {{ $lead->additional_email }}
                            </span>
                        @endif
                        <input type="email" name="additional_email" class="edit-field"
                            placeholder="Enter additional mail"
                            style="{{ $errors->has('additional_email') ? '' : 'display:none;' }}"
                            value="{{ old('additional_email') }}">
                    </span>
                    @error('additional_email')
                        <div class="text-danger">{{ $message }}</div>
                        <script>
                            // Show save button when there is an error
                            $(document).ready(function() {
                                $('.save-button').show();
                            });
                        </script>
                    @enderror
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.phone')</b>
                <a class="float-right">
                    @if (auth()->user()->is_channel_partner_manager && !empty($lead->phone))
                        {{ maskNumber($lead->phone) }}
                    @else
                        {{ $lead->phone ?? '' }}
                    @endif
                </a>
            </li>
            <li class="list-group-item">
                <b>{{ trans('messages.secondary_phone_key') }}</b>
                <a class="float-right">
                    <span class="value-container">
                        <span class="display-value"
                            style="{{ $errors->has('secondary_phone') ? 'display:none;' : '' }}">
                            {{ $lead->secondary_phone }}
                        </span>
                        <input type="phone" name="secondary_phone" class="edit-field"
                            placeholder="Enter Alternative Number"
                            style="{{ $errors->has('secondary_phone') ? '' : 'display:none;' }}"
                            value="{{ old('secondary_phone') }}">
                        @error('secondary_phone')
                            <div class="text-danger">{{ $message }}</div>
                            <script>
                                // Show save button when there is an error
                                $(document).ready(function() {
                                    $('.save-button').show();
                                });
                            </script>
                        @enderror
                    </span>
                </a>
            </li>
            {{-- <li class="list-group-item">
                <b>@lang('messages.secondary_phone_key')</b>
                <a class="float-right">
                    @if (auth()->user()->is_channel_partner_manager && !empty($lead->secondary_phone))
                        {{ maskNumber($lead->secondary_phone) }}
                    @else
                        {{ $lead->secondary_phone ?? '' }}
                    @endif
                </a>
               </li> --}}
            <li class="list-group-item">
                <b>{{ trans('cruds.lead.fields.project') }}</b>
                <a class="float-right">
                    {{ $lead->project->name ?? '' }}
                </a>
            </li>
            <li class="list-group-item">
                <b>{{ trans('cruds.lead.fields.campaign') }}</b>
                <a class="float-right">
                    {{ $lead->campaign->campaign_name ?? '' }}
                </a>
            </li>
            <li class="list-group-item">

                <b>{{ trans('messages.source') }}</b>
                <a class="float-right">
                    {{ $lead->source->name ?? '' }}
                </a>
            </li>

            <li class="list-group-item">
                <b>Intake Year</b>
                <a class="float-right">
                    <span class="value-container">
                        <span class="display-value">{{ $lead->intake_year }}</span>

                        <select name="intake_year" class="edit-field"
                            style="{{ $errors->has('intake_year') ? '' : 'display:none;' }}"
                            value="{{ old('intake_year') }}">
                            @if ($lead->intake_year)
                                <option value="{{ $lead->intake_year }}" selected>{{ $lead->intake_year }}
                                </option>
                            @else
                                <option value="" selected> Select Year</option>
                            @endif
                            @for ($startYear = date('Y'); $startYear >= 2000; $startYear--)
                                @php
                                    $endYear = $startYear + 1;
                                    $yearRange = $startYear . '-' . $endYear;
                                @endphp
                                <option value="{{ $yearRange }}">{{ $yearRange }}</option>
                            @endfor
                        </select>
                        @error('intake_year')
                            <div class="text-danger">{{ $message }}</div>
                            {{-- Show the save button when there is an error --}}
                            <script>
                                $(document).ready(function() {
                                    $('.save-button').show();
                                });
                            </script>
                        @enderror

                        {{-- Check if there are no errors related to 'intake_year' --}}
                    </span>
                </a>
            </li>
            <li class="list-group-item">
                <b>Grade enquired</b>
                <a class="float-right">
                    <span class="value-container">
                        <span class="display-value">{{ $lead->grade_enquired }}</span>

                        <select name="grade_enquired" class="edit-field"
                            style="{{ $errors->has('grade_enquired') ? '' : 'display:none;' }} ">

                            @if ($lead->grade_enquired)
                                <option value="{{ $lead->grade_enquired }}" selected>{{ $lead->grade_enquired }}
                                </option>
                            @else
                                <option value="" selected> Select Grade</option>
                            @endif

                            @for ($i = -2; $i <= 12; $i++)
                                @php
                                    if ($i == -2) {
                                        $grade = 'Pre-KG';
                                    } elseif ($i == -1) {
                                        $grade = 'LKG';
                                    } elseif ($i == 0) {
                                        $grade = 'UKG';
                                    } else {
                                        $grade = $i;
                                    }
                                @endphp
                                <option value="{{ $grade }}" @if (old('grade_enquired', $lead->grade_enquired) == $grade) selected @endif>
                                    {{ $grade }}
                                </option>
                            @endfor
                        </select>

                        @error('grade_enquired')
                            <div class="text-danger">{{ $message }}</div>
                            {{-- Show the save button when there is an error --}}
                            <script>
                                $(document).ready(function() {
                                    $('.save-button').show();
                                });
                            </script>
                        @enderror

                        {{-- Check if there are no errors related to 'intake_year' --}}
                    </span>
                </a>
            </li>
            <li class="list-group-item">
                <b>Child Name</b>
                <a class="float-right">
                    <span class="value-container">
                        <span class="display-value" style="{{ $errors->has('child_name') ? 'display:none;' : '' }}">
                            {{ $lead->child_name }}
                        </span>
                        <input type="text" name="child_name" class="edit-field" placeholder="Enter Current School"
                            style="{{ $errors->has('child_name') ? '' : 'display:none;' }}"
                            value="{{ old('child_name') }}">
                        @error('child_name')
                            <div class="text-danger">{{ $message }}</div>
                            {{-- Show the save button when there is an error --}}
                            <script>
                                $(document).ready(function() {
                                    $('.save-button').show();
                                });
                            </script>
                        @enderror
                    </span>
                </a>
            </li>
            <li class="list-group-item">
                <b>Current School</b>
                <a class="float-right">
                    <span class="value-container">
                        <span class="display-value"
                            style="{{ $errors->has('current_school') ? 'display:none;' : '' }}">
                            {{ $lead->current_school }}
                        </span>
                        <input type="text" name="current_school" class="edit-field"
                            placeholder="Enter Current School"
                            style="{{ $errors->has('current_school') ? '' : 'display:none;' }}"
                            value="{{ old('current_school') }}">
                        @error('current_school')
                            <div class="text-danger">{{ $message }}</div>
                            {{-- Show the save button when there is an error --}}
                            <script>
                                $(document).ready(function() {

                                    $('.save-button').show();
                                });
                            </script>
                        @enderror
                    </span>
                </a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <b>Stage</b>
                <div class="d-flex align-items-center">
                    <span style="margin-right: 1cm;">{{ $lead->parentStage->name ?? '' }}</span>
                    @if (!auth()->user()->is_client)
                        <button type="button" class="btn btn-primary rounded-circle p-2 d-flex align-items-center"
                            data-toggle="modal" data-target="#stageModal" style="width: 30px; height: 30px;">
                            <i class="fas fa-play fa-sm mx-auto"></i>
                        </button>
                    @endif
                </div>
            </li>
            @php
                $lead_info = $lead->lead_info;
                if (!empty($lead->source) && !empty($lead->source->name_key) && isset($lead_info[$lead->source->name_key]) && !empty($lead_info[$lead->source->name_key])) {
                    unset($lead_info[$lead->source->name_key]);
                }

                if (!empty($lead->source) && !empty($lead->source->email_key) && isset($lead_info[$lead->source->email_key]) && !empty($lead_info[$lead->source->email_key])) {
                    unset($lead_info[$lead->source->email_key]);
                }

                if (!empty($lead->source) && !empty($lead->source->phone_key) && isset($lead_info[$lead->source->phone_key]) && !empty($lead_info[$lead->source->phone_key])) {
                    unset($lead_info[$lead->source->phone_key]);
                }

                if (!empty($lead->source) && !empty($lead->source->additional_email_key) && isset($lead_info[$lead->source->additional_email_key]) && !empty($lead_info[$lead->source->additional_email_key])) {
                    unset($lead_info[$lead->source->additional_email_key]);
                }

                if (!empty($lead->source) && !empty($lead->source->secondary_phone_key) && isset($lead_info[$lead->source->secondary_phone_key]) && !empty($lead_info[$lead->source->secondary_phone_key])) {
                    unset($lead_info[$lead->source->secondary_phone_key]);
                }
            @endphp
            @foreach ($lead_info as $key => $value)
                <li class="list-group-item">
                    <b>{!! $key !!}</b>
                    <a class="float-right">
                        {!! $value !!}
                    </a>
                </li>
            @endforeach
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_created_date')</b>
                <a class="float-right">
                    @if (!empty($lead->sell_do_lead_created_at))
{{ @format_datetime($lead->sell_do_lead_created_at) }}
@endif
                </a>
            </li> -->
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_status')</b>
                <a class="float-right">
                    {!! $lead->sell_do_status ?? '' !!}
                </a>
            </li> -->
            <!-- <li class="list-group-item">
                <b>@lang('messages.sell_do_stage')</b>
                <a class="float-right">
                    {!! $lead->sell_do_stage ?? '' !!}
                </a>
            </li> -->
            <li class="list-group-item">
                <b>@lang('messages.customer_comments')</b>
                <a class="float-right">
                    {!! $lead->comments ?? '' !!}
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.cp_comments')</b>
                <a class="float-right">
                    {!! $lead->cp_comments ?? '' !!}
                </a>
            </li>
            <li class="list-group-item">
                <b>@lang('messages.added_by')</b>
                <a class="float-right">
                    {{ $lead->createdBy ? $lead->createdBy->name : '' }}
                </a>
            </li>
            <div class="modal fade" id="stageModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Child Stages</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('admin.leads.update', [$lead->id]) }}"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="modal-body">
                                <p><b>Parent Stage:</b> {{ $lead->parentStage->name ?? ' ' }}</p>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="tag_id">Select Tag:</label>
                                        <select name="tag_id" id="tag_id" class="form-control">
                                            <option value="" selected disabled>Please Select</option>
                                            @foreach ($tags as $tag)
                                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="parent_stage_id" id="selected_stage_id"
                                        value="{{ $lead->parentstage->id ?? '' }}">
                                    <div class="form-group">
                                        <label for="child_stage_id">Select Stage:</label>
                                        <select name="child_stage_id" id="child_stage_id" class="form-control"
                                            onchange="checkParentStageId(this)">
                                            <option value="" selected disabled>Please Select</option>
                                        </select>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="FollowupFormId" method="POST" action="{{ route('admin.followups.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="showfollowup" class="myDiv">
                                <!-- Your follow-up content goes here -->
                                <div>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="9">
                                    {{-- <div class="form-group">
                                            <label type="select" for="user_id">clients</label>
                                            <select name="user_id" id="user_id"
                                                class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                                rows="3" required>{{ old('user_id') }}
                                                >
                                                <option value="" selected disabled>Please
                                                    Select</option>
                                                @foreach ($agencies as $id => $agency)
                                                    @foreach ($agency->agencyUsers as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->representative_name }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div> --}}
                                    <div class="form-group">
                                        <label for="Date">Select Date </label>
                                        <input type="date"
                                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_date" id="follow_up_date" rows="3"
                                            required>{{ old('follow_up_date') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="Time">Select Time </label>
                                        <input type="time"
                                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_time" id="follow_up_time" rows="3"
                                            required>{{ old('follow_up_time') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="noteContent">Note Content</label>
                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="submit">
                                        Save
                                    </button>
                                </div>

                            </div>
                        </form>
                        <form id="SitevisitFormId" method="POST" action="{{ route('admin.sitevisit.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="showsitevisitScheduled" class="myDiv" style="display: none;">
                                <div>

                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="10">

                                    <div class="form-group">
                                        {{-- <label type="select" for="user_id">clients</label>
                                            <select name="user_id" id="user_id"
                                                class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                                rows="3" required>{{ old('user_id') }}
                                                >
                                                <option value="" selected disabled>Please
                                                    Select
                                                </option>
                                                @foreach ($client as $id => $clients)
                                                    @foreach ($clients->clientUsers as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                            {{ $user->representative_name }}
                                                        </option>
                                                    @endforeach
                                                @endforeach
                                            </select> --}}
                                        <div class="form-group">
                                            <label for="Date">Select Date </label>
                                            <input type="date"
                                                class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                                name="follow_up_date" id="follow_up_date" rows="3"
                                                required>{{ old('follow_up_date') }}
                                        </div>
                                        <label for="Time">Select Time</label>
                                        <input id="follow_up_time" name="follow_up_time" type="time"
                                            class="form-control timepicker" value="{{ old('follow_up_time') }}">
                                        <div class="form-group">
                                            <label for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>

                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                        <form id="SpamFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                            class="myForm" enctype="multipart/form-data">
                            @csrf
                            <div id="spamContent" style="display: none;">

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="15">
                                    <input type="hidden" name="stage" value="spam">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                        {{-- <select id="myselection" name="notes">
                                                <option>Select Option</option>
                                                @foreach ($noteNotInterested as $id => $notes)
                                                    <option value="{{ $notes->notes }}"
                                                        {{ old('notes_id') == $notes->id ? 'selected' : '' }}>
                                                        {{ $notes->notes }}
                                                    </option>
                                                @endforeach --}}
                                        {{-- <option value="Others">Others</option> --}}
                                        {{-- </select> --}}
                                        {{-- <div id="showOthers" class="myDiv">
                                                <label for="OthersNoteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                    rows="4" required>{{ old('notes') }}</textarea>
                                            </div> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="NotvisitedFormId" method="POST"
                            action="{{ route('admin.sitevisit.notvisited') }}" class="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="notvisitedContent" style="display: none;">
                                <div>
                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="12">
                                        <input type="hidden" name="stage" value="not_visited">

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class=float-left for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                    rows="4" required>{{ old('note_text') }}</textarea>
                                            </div>
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="ResheduleFormId" method="POST" action="{{ route('admin.sitevisit.reschedule') }}"
                            class="" enctype="multipart/form-data">
                            @csrf
                            <div id="showrescheduled" style="display: none;">
                                <!-- Your follow-up content goes here -->


                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id"
                                        value="19
                                                    ">

                                    <div class="form-group">
                                        <label for="Date">Select Date </label>
                                        <input type="date"
                                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_date" id="follow_up_date" rows="3"
                                            required>{{ old('follow_up_date') }}
                                    </div>

                                    <div class="form-group">
                                        <label for="Time">select time </label>
                                        <input type="time"
                                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_time" id="follow_up_time" rows="3"
                                            required>{{ old('follow_up_time') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="noteContent">Note Content</label>
                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="togglePopup()">Close</button>
                                    <button class="btn btn-danger" type="submit">
                                        Save
                                    </button>
                                </div>

                            </div>
                        </form>


                        <form id="ConductedFormId" method="POST" action="{{ route('admin.sitevisit.conducted') }}"
                            class="myForm" enctype="multipart/form-data">
                            @csrf
                            <div id="sitevisitconductedContent" style="display: none;">
                                <div>

                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="11">
                                        <input type="hidden" name="stage" value="site_visit_conducted">

                                        <div class="form-group">
                                            <label for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="CancelledFormId" method="POST" action="{{ route('admin.sitevisit.cancel') }}"
                            class="myForm" enctype="multipart/form-data">
                            @csrf
                            <div id="cancelledContent" style="display: none;">
                                <div>

                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="20">
                                        <input type="hidden" name="stage" value="site_visit_cancelled">

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class=float-left for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                    rows="4" required>{{ old('note_text') }}</textarea>
                                            </div>
                                            {{-- <select id="myselection">
                                                            <option>Select Option</option>
                                                            @foreach ($noteNotInterested as $id => $notes)
                                                                <option value="{{ $notes->notes }}"
                                                                    {{ old('notes_id') == $notes->id ? 'selected' : '' }}>
                                                                    {{ $notes->notes }}
                                                                </option>
                                                            @endforeach --}}
                                            {{-- <option value="Others">Others</option> --}}
                                            {{-- </select> --}}
                                            {{-- <div id="showOthers" class="myDiv">
                                                            <label for="otherNoteContent">Note Content</label>
                                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                                rows="4" required>{{ old('notes') }}</textarea>
                                                        </div> --}}
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="LostFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                            class="myForm" enctype="multipart/form-data">
                            @csrf
                            <div id="lostContent" style="display: none;">
                                <div>
                                    <h5 class="modal-title" id="followUpModalLabel">Lost
                                    </h5>

                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="17">
                                        <input type="hidden" name="stage" value="lost">

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class=float-left for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                    rows="4" required>{{ old('note_text') }}</textarea>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form id="applicationpurchasedFormId" method="POST"
                            action="{{ route('admin.applications.store') }}" class="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="applicationpurchasedContent" style="display: none;">
                                <div>
                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="13">
                                        <label for="application_no">Application Number:</label>
                                        <input class="form-control" type="text" name="application_no"
                                            value="">
                                        <label for="user_id">Select Representative:</label>
                                        <select
                                            class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                            name="user_id" id="user_id" required>
                                            @foreach ($users as $user)
                                                @if ($user->user_type == 'Admissionteam')
                                                    <option value="{{ $user->id }}"
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->representative_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="form-group">
                                            <label for="Date">Select Date </label>
                                            <input type="date"
                                                class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                                name="follow_up_date" id="follow_up_date" rows="3"
                                                required>{{ old('follow_up_date') }}
                                        </div>
                                        <div class="form-group">
                                            <label for="Time">Select Time </label>
                                            <input type="time"
                                                class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                                name="follow_up_time" id="follow_up_time" rows="3"
                                                required>{{ old('follow_up_time') }}
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form id="FututeprospectFormId" method="POST"
                            action="{{ route('admin.stage-notes.store') }}" class="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="futureprospectContent" style="display: none;">
                                <div>

                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="18">
                                        <input type="hidden" name="stage" value="future_prospect">

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class=float-left for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                    rows="4" required>{{ old('note_text') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="AdmittedFormId" method="POST" action="{{ route('admin.admitted.store') }}"
                            class="myForm" enctype="multipart/form-data">
                            @csrf
                            <div id="admittedContent" style="display: none;">
                                <div>
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <p> Lead ID: {{ $lead->id }}</p>

                                    <div class="modal-body">
                                        <input type="hidden" name="parent_stage_id" value="14">
                                        <div class="form-group">
                                            <label for="Date">Select Date </label>
                                            <input type="date"
                                                class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                                name="follow_up_date" id="follow_up_date" rows="3"
                                                required>{{ old('follow_up_date') }}
                                        </div>
                                        <div class="form-group">
                                            <label for="Time">Select Time </label>
                                            <input type="time"
                                                class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                                name="follow_up_time" id="follow_up_time" rows="3"
                                                required>{{ old('follow_up_time') }}
                                        </div>
                                        <div class="form-group">
                                            <label for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="AdmissionFollowupFormId" method="POST"
                            action="{{ route('admin.followups.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div id="showadmissionfollowup" class="myDiv" style="display: none;">

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="28">
                                    {{-- <div class="form-group">
                                                <label type="select" for="user_id">clients</label>
                                                <select name="user_id" id="user_id"
                                                    class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                                    rows="3" required>{{ old('user_id') }}
                                                    >
                                                    <option value="" selected disabled>Please
                                                        Select</option>
                                                    @foreach ($agencies as $id => $agency)
                                                        @foreach ($agency->agencyUsers as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                                {{ $user->representative_name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                    <div class="form-group">
                                        <label for="Date">Select Date </label>
                                        <input type="date"
                                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_date" id="follow_up_date" rows="3"
                                            required>{{ old('follow_up_date') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="Time">Select Time </label>
                                        <input type="time"
                                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_time" id="follow_up_time" rows="3"
                                            required>{{ old('follow_up_time') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="noteContent">Note Content</label>

                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" type="submit">
                                        Save
                                    </button>
                                </div>

                            </div>
                        </form>

                        <form id="NotqualifiedFormId" method="POST" action="{{ route('admin.notes.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="notqualifiedContent" style="display: none;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="followUpModalLabel">Not Qualified</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="1">
                                        <input type="hidden" name="stage" value="not_qualified">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class=float-left for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                    rows="4" required>{{ old('note_text') }}</textarea>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="rnrFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="rnrContent" style="display: none;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="followUpModalLabel">RNR</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="21">
                                        <input type="hidden" name="stage" value="rnr">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                    rows="4" required>{{ old('notes') }}</textarea>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-danger" type="submit">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </form>
                        <form id="SpamFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                            class="myForm" enctype="multipart/form-data">
                            @csrf
                            <div id="spamContent" style="display: none;">

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="15">
                                    <input type="hidden" name="stage" value="spam">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                        {{-- <select id="myselection" name="notes">
                                                <option>Select Option</option>
                                                @foreach ($noteNotInterested as $id => $notes)
                                                    <option value="{{ $notes->notes }}"
                                                        {{ old('notes_id') == $notes->id ? 'selected' : '' }}>
                                                        {{ $notes->notes }}
                                                    </option>
                                                @endforeach --}}
                                        {{-- <option value="Others">Others</option> --}}
                                        {{-- </select> --}}
                                        {{-- <div id="showOthers" class="myDiv">
                                                <label for="OthersNoteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                    rows="4" required>{{ old('notes') }}</textarea>
                                            </div> --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form id="NotvisitedFormId" method="POST"
                            action="{{ route('admin.sitevisit.notvisited') }}" class="myForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="notvisitedContent" style="display: none;">
                                <div>
                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="parent_stage_id" value="12">
                                        <input type="hidden" name="stage" value="not_visited">

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label class=float-left for="noteContent">Note Content</label>
                                                <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                    rows="4" required>{{ old('note_text') }}</textarea>
                                            </div>
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                    <form id="ResheduleFormId" method="POST" action="{{ route('admin.sitevisit.reschedule') }}"
                        class="" enctype="multipart/form-data">
                        @csrf
                        <div id="showrescheduled" style="display: none;">
                            <!-- Your follow-up content goes here -->
                            <div class="modal-body">
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <input type="hidden" name="parent_stage_id" value="19">

                                <div class="form-group">
                                    <label for="Date">Select Date </label>
                                    <input type="date"
                                        class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                        name="follow_up_date" id="follow_up_date" rows="3"
                                        required>{{ old('follow_up_date') }}
                                </div>

                                <div class="form-group">
                                    <label for="Time">select time </label>
                                    <input type="time"
                                        class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                        name="follow_up_time" id="follow_up_time" rows="3"
                                        required>{{ old('follow_up_time') }}
                                </div>
                                <div class="form-group">
                                    <label for="noteContent">Note Content</label>
                                    <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                        rows="4" required>{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" onclick="togglePopup()">Close</button>
                                <button class="btn btn-danger" type="submit">
                                    Save
                                </button>
                            </div>

                        </div>
                    </form>


                    <form id="ConductedFormId" method="POST" action="{{ route('admin.sitevisit.conducted') }}"
                        class="myForm" enctype="multipart/form-data">
                        @csrf
                        <div id="sitevisitconductedContent" style="display: none;">
                            <div>

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="11">
                                    <input type="hidden" name="stage" value="site_visit_conducted">

                                    <div class="form-group">
                                        <label for="noteContent">Note Content</label>
                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="CancelledFormId" method="POST" action="{{ route('admin.sitevisit.cancel') }}"
                        class="myForm" enctype="multipart/form-data">
                        @csrf
                        <div id="cancelledContent" style="display: none;">
                            <div>

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="20">
                                    <input type="hidden" name="stage" value="site_visit_cancelled">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                rows="4" required>{{ old('note_text') }}</textarea>
                                        </div>
                                        {{-- <select id="myselection">
                                                    <option>Select Option</option>
                                                    @foreach ($noteNotInterested as $id => $notes)
                                                        <option value="{{ $notes->notes }}"
                                                            {{ old('notes_id') == $notes->id ? 'selected' : '' }}>
                                                            {{ $notes->notes }}
                                                        </option>
                                                    @endforeach --}}
                                        {{-- <option value="Others">Others</option> --}}
                                        {{-- </select> --}}
                                        {{-- <div id="showOthers" class="myDiv">
                                                    <label for="otherNoteContent">Note Content</label>
                                                    <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                        rows="4" required>{{ old('notes') }}</textarea>
                                                </div> --}}
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="LostFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                        class="myForm" enctype="multipart/form-data">
                        @csrf
                        <div id="lostContent" style="display: none;">
                            <div>
                                <h5 class="modal-title" id="followUpModalLabel">Lost
                                </h5>

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="17">
                                    <input type="hidden" name="stage" value="lost">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                rows="4" required>{{ old('note_text') }}</textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="applicationpurchasedFormId" method="POST"
                        action="{{ route('admin.applications.store') }}" class="myForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="applicationpurchasedContent" style="display: none;">
                            <div>
                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="13">
                                    <label for="application_no">Application Number:</label>
                                    <input class="form-control" type="text" name="application_no" value="">
                                    <label for="user_id">Select Representative:</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                        name="user_id" id="user_id" required>
                                        @foreach ($users as $user)
                                            @if ($user->user_type == 'Admissionteam')
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->representative_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="form-group">
                                        <label for="Date">Select Date </label>
                                        <input type="date"
                                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_date" id="follow_up_date" rows="3"
                                            required>{{ old('follow_up_date') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="Time">Select Time </label>
                                        <input type="time"
                                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_time" id="follow_up_time" rows="3"
                                            required>{{ old('follow_up_time') }}
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label class=float-left for="noteContent">Note Content</label>
                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="FututeprospectFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                        class="myForm" enctype="multipart/form-data">
                        @csrf
                        <div id="futureprospectContent" style="display: none;">
                            <div>

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="18">
                                    <input type="hidden" name="stage" value="future_prospect">

                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                rows="4" required>{{ old('note_text') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="AdmittedFormId" method="POST" action="{{ route('admin.admitted.store') }}"
                        class="myForm" enctype="multipart/form-data">
                        @csrf
                        <div id="admittedContent" style="display: none;">
                            <div>
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <p> Lead ID: {{ $lead->id }}</p>

                                <div class="modal-body">
                                    <input type="hidden" name="parent_stage_id" value="14">
                                    <div class="form-group">
                                        <label for="Date">Select Date </label>
                                        <input type="date"
                                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_date" id="follow_up_date" rows="3"
                                            required>{{ old('follow_up_date') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="Time">Select Time </label>
                                        <input type="time"
                                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_time" id="follow_up_time" rows="3"
                                            required>{{ old('follow_up_time') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="noteContent">Note Content</label>
                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="AdmissionFollowupFormId" method="POST" action="{{ route('admin.followups.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="showadmissionfollowup" class="myDiv" style="display: none;">

                            <div class="modal-body">
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <input type="hidden" name="parent_stage_id" value="28">
                                {{-- <div class="form-group">
                                        <label type="select" for="user_id">clients</label>
                                        <select name="user_id" id="user_id"
                                            class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                            rows="3" required>{{ old('user_id') }}
                                            >
                                            <option value="" selected disabled>Please
                                                Select</option>
                                            @foreach ($agencies as $id => $agency)
                                                @foreach ($agency->agencyUsers as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->representative_name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div> --}}
                                <div class="form-group">
                                    <label for="Date">Select Date </label>
                                    <input type="date"
                                        class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                        name="follow_up_date" id="follow_up_date" rows="3"
                                        required>{{ old('follow_up_date') }}
                                </div>
                                <div class="form-group">
                                    <label for="Time">Select Time </label>
                                    <input type="time"
                                        class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                        name="follow_up_time" id="follow_up_time" rows="3"
                                        required>{{ old('follow_up_time') }}
                                </div>
                                <div class="form-group">
                                    <label for="noteContent">Note Content</label>

                                    <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                        rows="4" required>{{ old('notes') }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="submit">
                                    Save
                                </button>
                            </div>

                        </div>
                    </form>

                    <form id="NotqualifiedFormId" method="POST" action="{{ route('admin.notes.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="notqualifiedContent" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="followUpModalLabel">Not Qualified</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="1">
                                    <input type="hidden" name="stage" value="not_qualified">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class=float-left for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                                rows="4" required>{{ old('note_text') }}</textarea>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="rnrFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="rnrContent" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="followUpModalLabel">RNR</h5>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="21">
                                    <input type="hidden" name="stage" value="rnr">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form id="applicationnotpurchasedFormId" method="POST"
                        action="{{ route('admin.applications.store') }}" class="myForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="applicationnotpurchasedContent" style="display: none;">
                            <div>
                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="30">

                                    <label for="user_id">Select Representative:</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                                        name="user_id" id="user_id" required>
                                        @foreach ($users as $user)
                                            @if ($user->user_type == 'Admissionteam')
                                                <option value="{{ $user->id }}"
                                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->representative_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="form-group">
                                        <label for="Date">Select Date </label>
                                        <input type="date"
                                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_date" id="follow_up_date" rows="3"
                                            required>{{ old('follow_up_date') }}
                                    </div>
                                    <div class="form-group">
                                        <label for="Time">Select Time </label>
                                        <input type="time"
                                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                                            name="follow_up_time" id="follow_up_time" rows="3"
                                            required>{{ old('follow_up_time') }}
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label class=float-left for="noteContent">Note Content</label>
                                        <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                            rows="4" required>{{ old('notes') }}</textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="submit">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="AdmissionFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="admissionwithdrawnContent" style="display: none;">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                    <input type="hidden" name="parent_stage_id" value="29">
                                    <input type="hidden" name="stage" value="application withdrawn">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label for="noteContent">Note Content</label>
                                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                                rows="4" required>{{ old('notes') }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-danger" type="submit">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>




        </ul>
    </div>
</div>



@section('scripts')
    <script>
        $(document).ready(function() {
            $('.edit-button').on('click', function() {
                // Show the edit fields and set border color to red
                $('.edit-field').show().css('border-color', 'red');
                $('.display-value').hide();
                $('.save-button').show();
                $('.edit-field').each(function() {
                    var fieldName = $(this).attr('name');
                    var displayValue = $(this).siblings('.display-value').text();
                    $(this).val(displayValue);
                });
                // Clear any previous error messages
                $('.error-message').text('');
            });

            $('.save-button').on('click', function() {
                // Create an object to store the updated values
                var updatedValues = {};
                var isValid = true;
                // Update the display values with the entered values and store in the object
                $('.display-value').each(function() {
                    var fieldName = $(this).attr('name');
                    var updatedValue = $(this).siblings('.edit-field').val();

                    // Perform validation for each field
                    switch (fieldName) {
                        case 'additional_email':
                            if (!isValidEmail(updatedValue)) {
                                isValid = false;
                                $('.error-message').text('Invalid email address.');
                            }
                            break;
                        case 'secondary_phone':
                            if (!isValidPhoneNumber(updatedValue)) {
                                isValid = false;
                                $('.error-message').text(
                                    'Invalid phone number (10 digits required).');
                            }
                            break;
                        case 'intake_year':
                            // Add validation logic if needed
                            break;
                        case 'grade_enquired':
                            // Add validation logic if needed
                            break;

                        case 'current_school':
                            // Add validation logic if needed
                            break;
                            // Add more cases for other fields as needed
                    }

                    updatedValues[fieldName] = updatedValue;
                });
                if (updatedValue.trim() === '') {
                    $(this).siblings('.edit-field').css('border-color', 'red');
                } else {
                    $(this).siblings('.edit-field').css('border-color', 'blue');
                }

                // Add the _method field for Laravel to recognize it as a PUT request
                updatedValues['_method'] = 'PUT';
                // Check if all fields are valid before making the AJAX request
                if (isValid) {
                    $.ajax({
                        method: 'POST',
                        url: '{{ route('admin.leads.update', [$lead->id]) }}',
                        data: updatedValues,
                        success: function(response) {
                            // Handle success if needed
                            console.log(response);
                            // Hide the text fields and show the display values
                            $('.edit-field').hide();
                            $('.display-value').show();
                        },
                        error: function(error) {
                            // Log the error response
                            console.error(error.responseJSON);
                            // Show the text fields and hide the display values
                            $('.edit-field').show();
                            $('.display-value').hide();
                        }
                    });
                }
            });
        });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function checkParentStageId(selectElement) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var selectedName = selectedOption.text.trim().toLowerCase();

        // Hide all modals initially

        document.getElementById('showfollowup').style.display = 'none';
        document.getElementById('showrescheduled').style.display = 'none';
        document.getElementById('showsitevisitScheduled').style.display = 'none';
        document.getElementById('showadmissionfollowup').style.display = 'none';
        document.getElementById('notvisitedContent').style.display = 'none';
        document.getElementById('spamContent').style.display = 'none';
        document.getElementById('notqualifiedContent').style.display = 'none';
        document.getElementById('lostContent').style.display = 'none';
        document.getElementById('futureprospectContent').style.display = 'none';
        document.getElementById('cancelledContent').style.display = 'none';
        document.getElementById('rnrContent').style.display = 'none';
        document.getElementById('sitevisitconductedContent').style.display = 'none';
        document.getElementById('applicationpurchasedContent').style.display = 'none';
        document.getElementById('showrescheduled').style.display = 'none';
        document.getElementById('admittedContent').style.display = 'none';
        document.getElementById('applicationnotpurchasedContent').style.display = 'none';
        document.getElementById('admissionwithdrawnContent').style.display = 'none';
        // Check the selected option and display the corresponding modal
        if (selectedName === 'followup') {
            $("#showfollowup").show();
        } else if (selectedName === 'site visit scheduled') {
            $("#showsitevisitScheduled").show();
        } else if (selectedName === 'admission followup') {
            $("#showadmissionfollowup").show();
        } else if (selectedName === 'rescheduled') {
            $("#showrescheduled").show();
        } else if (selectedName === 'site not visited') {
            document.getElementById('notvisitedContent').style.display = 'block';
        } else if (selectedName === 'spam') {
            document.getElementById('spamContent').style.display = 'block';
        } else if (selectedName === 'not qualified') {
            document.getElementById('notqualifiedContent').style.display = 'block';
        } else if (selectedName === 'lost') {
            document.getElementById('lostContent').style.display = 'block';
        } else if (selectedName === 'future prospect') {
            document.getElementById('futureprospectContent').style.display = 'block';
        } else if (selectedName === 'cancelled') {
            document.getElementById('cancelledContent').style.display = 'block';
        } else if (selectedName === 'rnr') {
            document.getElementById('rnrContent').style.display = 'block';
        } else if (selectedName === 'site visit conducted') {
            document.getElementById('sitevisitconductedContent').style.display = 'block';
        } else if (selectedName === 'application purchased') {
            document.getElementById('applicationpurchasedContent').style.display = 'block';
        } else if (selectedName === 'admitted') {
            $("#admittedContent").show();
        } else if (selectedName === 'application not purchased') {
            document.getElementById('applicationnotpurchasedContent').style.display = 'block';
        } else if (selectedName === 'admission withdrawn') {
            document.getElementById('admissionwithdrawnContent').style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        checkParentStageId(document.getElementById('parent_stage_id'));
    });

    $(document).ready(function() {
        $('#tag_id').change(function() {
            var selectedTagId = $('#tag_id option:selected').val();
            // Check if childStages is not null
            var childStages = {!! json_encode($lead->parentStage->childStages ?? null) !!};
            console.log("Child Stages:", childStages);
            var selectedChildStages = [];

            // Check if childStages is not null and has the selected_child_stages property
            if (childStages && childStages.length > 0 && childStages[0].selected_child_stages) {
                selectedChildStages = JSON.parse(childStages[0].selected_child_stages);
            }

            console.log("Selected Child Stages:", selectedChildStages);

            $('#child_stage_id').html(
                '<option value="" selected disabled>Please Select a Tag First</option>');

            if (selectedTagId && selectedChildStages) {
                // Filter child stages based on the selected tag and lead's selected_child_stages
                @foreach ($parentStages as $stage)
                    if ("{{ $stage->tag_id }}" == selectedTagId && selectedChildStages.includes(
                            "{{ $stage->id }}")) {
                        $('#child_stage_id').append(
                            '<option value="{{ $stage->id }}" data-tag="{{ $stage->tag_id }}">{{ $stage->name }}</option>'
                        );
                    }
                @endforeach
            }
        });
    });
</script>

<style>
    .myDiv {
        display: none;
        padding: 10px;
        margin-top: 20px;
    }


    #showOther {}

    #showfollowup {}

    #showsitevisitScheduled {}
</style>
