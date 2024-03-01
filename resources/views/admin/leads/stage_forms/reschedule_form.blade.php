<form id="ResheduleFormId" method="POST" action="{{ route('admin.sitevisit.reschedule') }}" class=""
            enctype="multipart/form-data">
            @csrf
            <div id="showrescheduled" style="display: none;">
                <!-- Your follow-up content goes here -->
                <div class="modal-body">
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    <input type="hidden" name="stage_id" value="19">

                    <div class="form-group">
                        <label for="Date">Select Date </label>
                        <input type="date"
                            class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                            name="visit_date" id="visit_date" rows="3"
                            required>{{ old('visit_date') }}
                    </div>

                    <div class="form-group">
                        <label for="Time">select time </label>
                        <input type="time"
                            class="form-control timepicker {{ $errors->has('form-control timepicker') ? 'is-invalid' : '' }}"
                            name="visit_time" id="visit_time" rows="3"
                            required>{{ old('visit_time') }}
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
        @includeIf('admin.leads.partial.stage_forms')
