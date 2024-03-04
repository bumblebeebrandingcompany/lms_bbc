<form id="CancelledFormId" method="POST" action="{{ route('admin.sitevisit.cancel') }}" class="myForm"
enctype="multipart/form-data">
@csrf
<div id="cancelledContent" style="display: none;">
    <div>

        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="stage_id" value="20">
            <input type="hidden" name="stage" value="site_visit_cancelled">

            <div class="form-group">
                <div class="form-group">
                    <label class=float-left for="noteContent">Note Content</label>
                    <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                        rows="4" required>{{ old('notes') }}</textarea>
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
