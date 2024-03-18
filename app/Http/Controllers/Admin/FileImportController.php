<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Carbon\Carbon;

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
            $createdAt = !empty($row[6]) ? Carbon::createFromFormat('d-m-Y', $row[6])->format('Y-m-d H:i:s') : null;

            $dataToInsert[] = [
                'lead_id' => $row[0],
                'application_no' => $row[1],
                'application_date' => $row[2],
                'stage_id' => $row[3],
                'who_assigned' => $row[4],
                'for_whom' => $row[5],
                'created_at' => $createdAt,
                'application_time' => $row[7],
            ];

            \Log::info('Row Data: ' . json_encode($row)); // Add this line for logging row data
        }

        // Log the data before insertion
        \Log::info('Data to Insert: ' . json_encode($dataToInsert));

        // Bulk insert into the database
        Application::insert($dataToInsert);

        return redirect()->back()->with('success', 'CSV data imported successfully.');
    }

    return redirect()->back()->with('error', 'Failed to import CSV data.');
}

}
