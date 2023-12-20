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

        return view('admin.leads.partials.notes', compact('note_text','lead','note','campaigns'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id',
            'note_text' => 'required',
            'lead_id' => 'required',
        ]);

        $lead = Lead::find($request->lead_id);

        // Create a new note record in your database
        $note = new Note();
        $note->id = $request->id;
        $note->note_text = $request->note_text;
        $note->lead_id = $lead->id;
        $note->save();

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

}

