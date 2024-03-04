<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\StageNotes;
use Illuminate\Http\Request;

class StageNotesController extends Controller
{


    public function store(Request $request)
    {
        $lead = Lead::find($request->lead_id);

        if ($lead) {
            $parentStageId = $request->input('stage_id');

            $note = new StageNotes();
            $note->lead_id = $lead->id;
            $note->stage_id = $parentStageId;
            $note->notes = $request->input('notes');
            $note->stage = $request->input('stage');
            $note->save();
            $lead->stage_id = $parentStageId;
            $lead->save();

            $this->logTimeline($lead, $note, 'Stage Changed', "Stage was updated to {$parentStageId}");


            return redirect()->back()->with('success', 'Form submitted successfully!');
        } else {
            // Handle the case where the lead is not found
            return redirect()->back()->with('error', 'Lead not found!');
        }
    }

    private function logTimeline(Lead $lead, $notes, $type, $description)
    {
        $timeline = new LeadTimeline;
        $timeline->activity_type = $type;
        $timeline->lead_id = $lead->id;

        // Combine lead and site visit data into payload
        $payload = [
            'lead' => $lead->toArray(),
            'notes' => $notes->toArray()
        ];

        $timeline->payload = json_encode($payload); // Convert array to JSON
        // $timeline->description = $description;
        $timeline->save();
    }

}
