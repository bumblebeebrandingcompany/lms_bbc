<form id="AdmissionwithdrawnFormId" method="POST"
action="{{ route('admin.stage-notes.store') }}" class="myForm"
enctype="multipart/form-data">
@csrf
<div id="admissionwithdrawnContent" style="display: none;">
    <div>

        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="stage_id" value="29">
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
