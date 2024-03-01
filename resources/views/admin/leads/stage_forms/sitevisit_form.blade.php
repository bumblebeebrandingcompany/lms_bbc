<form id="SitevisitFormId" method="POST" action="{{ route('admin.sitevisit.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div id="showsitevisitScheduled" class="myDiv" style="display: none;">
                                    <div>

                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                        <input type="hidden" name="stage_id" value="10">

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="Date">Select Date </label>
                                                <input type="date"
                                                    class="form-control datepicker {{ $errors->has('form-control datepicker') ? 'is-invalid' : '' }}"
                                                    name="visit_date" id="visit_date" rows="3"
                                                    required>{{ old('visit_date') }}
                                            </div>
                                            <label for="Time">Select Time</label>
                                            <input id="visit_time" name="visit_time" type="time"
                                                class="form-control timepicker" value="{{ old('visit_time') }}">
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
                            @includeIf('admin.leads.partial.stage_forms')
