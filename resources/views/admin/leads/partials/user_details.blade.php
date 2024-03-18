<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stages Popup</title>

<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div style="text-align: right;">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <div class="text-left">
                @php
                    $avatar = 'https://ui-avatars.com/api/?background=random&font-size=0.7&name=' . $lead->name;
                @endphp
                <img class="profile-user-img img-fluid img-circle" src="{{ $avatar }}"
                    alt="{{ $lead->name ?? '' }}">
            </div>
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

        <form method="POST" action="{{ route('admin.leads.update', [$lead->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <button type="submit" class="float-right save-button btn-success" style="display:none;">Save</button>
            <h3 class="profile-username text-center">
                {{ $lead->name ?? '' }}
            </h3>
            <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                    <b>{{ trans('messages.ref_num') }}</b>
                    <a class="float-right">{{ $lead->ref_num }}</a>
                </li>

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

                <li class="list-group-item">
                    <b>{{ trans('messages.additional_email_key') }}</b>
                    <a class="float-right">

                        <span class="value-container">
                            @if (!$errors->has('secondary_email'))
                                <span class="display-value">
                                    {{ $lead->secondary_email }}
                                </span>
                            @endif
                            <input type="email" name="secondary_email" class="edit-field"
                                placeholder="Enter additional mail"
                                style="{{ $errors->has('secondary_email') ? '' : 'display:none;' }}"
                                value="{{ old('secondary_email') }}">
                        </span>
                        @error('secondary_email')
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
                <li class="list-group-item">
                    <b>Address</b>
                    <a class="float-right">
                        <span class="value-container">
                            <span class="display-value" style="{{ $errors->has('address') ? 'display:none;' : '' }}">
                                {{ $lead->address }}
                            </span>
                            <input type="text" name="address" class="edit-field" placeholder="Enter Current School"
                                style="{{ $errors->has('address') ? '' : 'display:none;' }}"
                                value="{{ old('address') }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
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
                                    <option value="{{ $grade }}"
                                        @if (old('grade_enquired', $lead->grade_enquired) == $grade) selected @endif>
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
                            <span class="display-value"
                                style="{{ $errors->has('child_name') ? 'display:none;' : '' }}">
                                {{ $lead->child_name }}
                            </span>
                            <input type="text" name="child_name" class="edit-field"
                                placeholder="Enter Current School"
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
                                    <option value="{{ $grade }}"
                                        @if (old('', $lead->grade_enquired) == $grade) selected @endif>
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
                    <b>Child Gender</b>
                    <a class="float-right">
                        <span class="value-container">
                            <span class="display-value">{{ $lead->child_gender }}</span>
                            <div class="form-group">
                                <select name="child_gender" class="edit-field"
                                    style="{{ $errors->has('child_gender') ? '' : 'display:none;' }} ">
                                    @if ($lead->child_gender)
                                        <option value="{{ $lead->child_gender }}" selected>{{ $lead->child_gender }}
                                        </option>
                                    @else
                                        <option value="" selected> Select Gender</option>
                                    @endif

                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>


                            </div>
                        </span>
                    </a>
                </li>

                <li class="list-group-item">
                    <b>Child Age</b>
                    <a class="float-right">
                        <span class="value-container">
                            <span class="display-value"
                                style="{{ $errors->has('child_age') ? 'display:none;' : '' }}">
                                {{ $lead->child_age }}
                            </span>
                            <input type="text" name="child_age" class="edit-field"
                                placeholder="Enter Current School"
                                style="{{ $errors->has('child_age') ? '' : 'display:none;' }}"
                                value="{{ old('child_age') }}">
                            @error('child_age')
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
                    <b>Previous School</b>
                    <a class="float-right">
                        <span class="value-container">
                            <span class="display-value"
                                style="{{ $errors->has('previous_school') ? 'display:none;' : '' }}">
                                {{ $lead->previous_school }}
                            </span>
                            <input type="text" name="previous_school" class="edit-field"
                                placeholder="Enter Current School"
                                style="{{ $errors->has('previous_school') ? '' : 'display:none;' }}"
                                value="{{ old('previous_school') }}">
                            @error('previous_school')
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
                    <b>Previous School location</b>
                    <a class="float-right">
                        <span class="value-container">
                            <span class="display-value"
                                style="{{ $errors->has('previous_school_location') ? 'display:none;' : '' }}">
                                {{ $lead->previous_school_location }}
                            </span>
                            <input type="text" name="previous_school_location" class="edit-field"
                                placeholder="Enter Current School"
                                style="{{ $errors->has('previous_school_location') ? '' : 'display:none;' }}"
                                value="{{ old('previous_school_location') }}">
                            @error('previous_school_location')
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
                            <button type="button"
                                class="btn btn-primary rounded-circle p-2 d-flex align-items-center"
                                data-toggle="modal" data-target="#stageModal" style="width: 30px; height: 30px;">
                                <i class="fas fa-play fa-sm mx-auto"></i>
                            </button>
                        @endif
                    </div>
                </li>
                <li class="list-group-item">
                    <b>@lang('messages.customer_comments')</b>
                    <a class="float-right">
                        {!! $lead->comments ?? '' !!}
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
                                        <input type="hidden" name="stage_id" id="selected_stage_id"
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
                            @includeIf('admin.leads.stage_forms.followup_form')
                            @includeIf('admin.leads.stage_forms.sitevisit_form')
                            @includeIf('admin.leads.stage_forms.reschedule_form')
                            @includeIf('admin.leads.stage_forms.spam_form')
                            @includeIf('admin.leads.stage_forms.application_form')
                            @includeIf('admin.leads.stage_forms.application_not_purchased_form')
                            @includeIf('admin.leads.stage_forms.site_not_visited_form')
                            @includeIf('admin.leads.stage_forms.site_visit_conducted_form')
                            @includeIf('admin.leads.stage_forms.cancelled_form')
                            @includeIf('admin.leads.stage_forms.lost_form')
                            @includeIf('admin.leads.stage_forms.admission_form')
                            @includeIf('admin.leads.stage_forms.admission_followup_form')
                            @includeIf('admin.leads.stage_forms.not_qualified_form')
                            @includeIf('admin.leads.stage_forms.admission_withdrawn_form')
                            @includeIf('admin.leads.stage_forms.future_prospect_form')
                            @includeIf('admin.leads.stage_forms.rnr_form')
                        </div>
                    </div>
                </div>

            </ul>
        </form>
    </div>



</div>







@section('scripts')
    @includeIf('admin.leads.partials.stage_form_js')
@endsection
