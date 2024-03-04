<form id="applicationpurchasedFormId" method="POST" action="{{ route('admin.applications.store') }}"
class="myForm" enctype="multipart/form-data">
@csrf
<div id="applicationpurchasedContent" style="display: none;">
    <div>
        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="stage_id" value="13">
            <label for="application_no">Application Number:</label>
            <input class="form-control" type="text" name="application_no" value="" required>
            <label for="user_id">Select Representative:</label>
            <select class="form-control select2 {{ $errors->has('client') ? 'is-invalid' : '' }}"
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
