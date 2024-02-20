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
        $lead = Lead::find($request->lead_id);
        if ($lead) {
            $parentStageId = $request->input('parent_stage_id');
            $note = new Note();
            $note->lead_id = $lead->id;
            $note->parent_stage_id = $parentStageId;
            $note->note_text = $request->input('note_text');
            $note->save();

            // Update the parent_stage_id in the leads table
            $lead->parent_stage_id = $parentStageId;
            $lead->save();

            // Log the timeline event
            // $note->logTimeline($lead->id, 'Note added', 'note_added');

            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }
}

