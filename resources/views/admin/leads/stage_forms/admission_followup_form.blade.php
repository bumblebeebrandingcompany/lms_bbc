<form id="AdmissionFollowupFormId" method="POST" action="{{ route('admin.followups.store') }}"
enctype="multipart/form-data">
@csrf
<div id="showadmissionfollowup" class="myDiv" style="display: none;">

    <div class="modal-body">
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <input type="hidden" name="stage_id" value="28">
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
