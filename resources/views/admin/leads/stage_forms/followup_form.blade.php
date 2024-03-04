<form id="FollowupFormId" method="POST" action="{{ route('admin.followups.store') }}"
enctype="multipart/form-data">
@csrf
<div id="showfollowup" class="myDiv">
    <!-- Your follow-up content goes here -->
    <div>
    </div>
    <div class="modal-body">
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <input type="hidden" name="stage_id" value="9">

        <div class="form-group">
            <label for="Date">Select Date </label>
            <input type="date"
                class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                name="followup_date" id="followup_date" rows="3"
                required>{{ old('followup_date') }}
        </div>
        <div class="form-group">
            <label for="Time">Select Time </label>
            <input type="time"
                class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                name="followup_time" id="followup_time" rows="3"
                required>{{ old('followup_time') }}
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

@includeIf('admin.leads.partial.stage_forms')
