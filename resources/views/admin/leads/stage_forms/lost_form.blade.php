<form id="LostFormId" method="POST" action="{{ route('admin.stage-notes.store') }}" class="myForm"
enctype="multipart/form-data">
@csrf
<div id="lostContent" style="display: none;">
    <div>
        <h5 class="modal-title" id="followUpModalLabel">Lost
        </h5>

        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="stage_id" value="17">
            <input type="hidden" name="stage" value="lost">

            <div class="form-group">
                <div class="form-group">
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
