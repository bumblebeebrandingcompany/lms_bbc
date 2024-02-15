<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Campaign;

use App\Models\Lead;

use App\Utils\Util;
use App\Models\Note;


use Illuminate\Http\Request;


class NoteController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    public function __construct(Util $util)
    {
        $this->util = $util;

    }
    public function index()
    {
        $note = Note::all();
        $lead = Lead::all();
        $notes = Note::all();
        $campaigns = Campaign::all();
        $itemsPerPage = request('perPage', 10);
        $notes = Note::paginate($itemsPerPage);
        return view('admin.leads.partials.notes', compact('note_text','lead','note','campaigns',));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'note_text'=>'required',
            'lead_id' => 'required',

        ]);

        $lead = Lead::find($request->lead_id);

        // Create a new note record in your database
        $note = new Note();

        // Set the note_text based on the dropdown selection
        $noteText = $request->input('note_text');
        $noteContent = $request->input('note_content');

        // Debugging statements

        $note->lead_id = $lead->id;

        // $note->note_content = $request->note_content; // Save the selected option or "Others"
        $note->save();

        // Log the timeline event
        $note->logTimeline($lead->id, 'Note added', 'note_added');

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }



}

