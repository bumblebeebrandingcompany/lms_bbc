<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Stage;
use App\Models\ParentStage;
use Illuminate\Http\Request;

class StagesController extends Controller
{
    public function index()
    {
        $parentStages = ParentStage::all();
        $stages = Stage:: all();
        return view('admin.stages.index', compact('parentStages','stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_id' => 'required|exists:parent_stages,id',
            'selected_child_stages' => 'required|array',
        ]);

        $stage = new Stage([
            'stage_id' => $request->stage_id,
            'selected_child_stages' => json_encode($request->selected_child_stages),
        ]);

        $stage->save();

        return redirect()->route('admin.stages.index')->with('success', 'Stage created successfully.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'stage_id' => 'required|exists:parent_stages,id',
            'selected_child_stages' => 'required|array',
        ]);

        $stage = Stage::findOrFail($id);
        $stage->update([
            'stage_id' => $request->stage_id,
            'selected_child_stages' => json_encode($request->selected_child_stages),
        ]);

        return redirect()->route('admin.stages.index')->with('success', 'Stage updated successfully.');
    }

    public function destroy($id)
    {
        $stage = Stage::findOrFail($id);
        $stage->delete();

        return redirect()->route('admin.stages.index')->with('success', 'Stage deleted successfully.');
    }
}
