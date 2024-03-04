<form id="NotqualifiedFormId" method="POST" action="{{ route('admin.stage-notes.store') }}"
enctype="multipart/form-data">
@csrf
<div id="notqualifiedContent" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="followUpModalLabel">Not Qualified</h5>
        </div>
        <div class="modal-body">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <input type="hidden" name="stage_id" value="16">
            <input type="hidden" name="stage" value="not_qualified">
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
