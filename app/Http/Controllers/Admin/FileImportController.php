<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Imports\FilesImport;
use App\Models\FileImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;




class FileImportController extends Controller
{

    public function index()
    {
        return view('admin.import.index');
    }
    public function store(Request $request)
{
    // Validate the uploaded file
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048', // adjust max file size as needed
    ]);

    // Process the CSV file
    if ($request->hasFile('csv_file')) {
        $file = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($file));

        // Remove header row if needed
        $header = array_shift($csvData);

        // Extract first_name and last_name columns
        $dataToInsert = [];
        foreach ($csvData as $row) {
            $dataToInsert[] = [
                'first_name' => $row[0], // Assuming first_name is in the first column
                'last_name' => $row[1],  // Assuming last_name is in the second column
            ];
        }

        // Bulk insert into the database
        FileImport::insert($dataToInsert);

        return redirect()->back()->with('success', 'CSV data imported successfully.');
    }

    return redirect()->back()->with('error', 'Failed to import CSV data.');
}

}


