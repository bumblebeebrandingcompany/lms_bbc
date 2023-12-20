<div class="form-group">
    <p>Notes for Lead ID: {{ $lead->id }}</p>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNoteModal">
        Add Note
    </button>
    <br>
    <br>
    <div class="modal fade" id="addNoteModal" tabindex="-1" role="dialog" aria-labelledby="addNoteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNoteModalLabel">Add Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.notes.store') }}" enctype="multipart/form-data">
                    @csrf
                <div class="modal-body">

                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <h3 class="card-title"> Notes for Lead ID: {{ $lead->id }}</h3>
                        <br>

                        <div class="form-group">
                            <label for="noteContent">Note Content</label>
                            <textarea class="form-control {{ $errors->has('note_text') ? 'is-invalid' : '' }}" name="note_text" id="note_text"
                                rows="4" required>{{ old('note_text') }}</textarea>
                        </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div id="savedNotesContainer" class="saved-notes-container">
            <!-- Display saved notes here -->
            @foreach ($note as $note)
                <div class="saved-note">
                    <label for="note_text" class="saved-note-label">Note:</label>
                    <div class="saved-note-text">{{ $note->note_text }}</div>

                    <label for="created at" class="saved-note-label">Date:</label>
                    <div class="saved-created_at">{{ $note->created_at }}</div>
                </div>
            @endforeach
        </div>
    </div>

    </form>
</div>
