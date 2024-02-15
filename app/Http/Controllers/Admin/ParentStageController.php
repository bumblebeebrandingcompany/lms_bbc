<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ParentStage;
use App\Models\Tag;
use App\Models\Stage;

use Illuminate\Http\Request;

class ParentStageController extends Controller
{
    public function index()
    {
        $parentStages = ParentStage::all();
        $tags = Tag::all();
        return view('admin.parent_stages.index', compact('parentStages','tags'));
    }
    public function create()
    {

        $parentStages = ParentStage::all();
        $stages = Stage:: all();
        return view('admin.parent_stages.create', compact('parentStages','stages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tag_id' => 'required|exists:tags,id',
        ]);

        ParentStage::create([
            'name' => $request->name,
            'tag_id' => $request->tag_id,
        ]);

        return redirect()->route('admin.parent-stages.index')->with('success', 'Parent Stage created successfully.');
    }

    public function edit($id)
    {
        $parentStage = ParentStage::findOrFail($id);
        $tags = Tag::all(); // Assuming you have a Tag model

        return view('admin.parent-stages.edit', compact('parentStage', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tag_id' => 'required|exists:tags,id',
            // Add validation rules for other fields as needed
        ]);

        $parentStage = ParentStage::findOrFail($id);

        $parentStage->name = $request->input('name');
        $parentStage->tag_id = $request->input('tag_id');
        // Update other fields as needed

        $parentStage->save();

        return redirect()->route('admin.parent-stages.index')->with('success', 'Parent Stage updated successfully');
    }

    public function destroy($id)
    {
        $stage = ParentStage::findOrFail($id);
        $stage->delete();

        return redirect()->route('admin.parent-stages.index')->with('success', 'Stage deleted successfully.');
    }

}
