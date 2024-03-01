<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Lead;
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
                'sno' => $row[0],
                'sub_source_id' => json_encode($row[1] ?? null),
                'ref_num' => $row[2]??'',
                'child_name' => $row[3]?? '',
                'dob' => $row[4]??'',
                'child_gender' => $row[5]?? '',
                'grade_enquired' => $row[6] ?? '',
                'father_name' => $row[7]?? '',
                'father_occupation' => $row[8]?? '',
                'email' => $row[9]?? '',
                'secondary_email' => $row[10]?? '',
                'mother_name' => $row[11]?? '',
                'mother_occupation' => $row[12]?? '',
                'address' => $row[13]??'',
                'phone' =>$row[14]??'',
                'secondary_phone' =>$row[15]??'',
                'comments' => $row[16]??'',
                'added_by' => $row[17]??'',
                'reference' => $row[18]??'',
                'previous_school' => $row[19]??'',
                'board' => $row[20] ?? '',
                'previous_school_location' => $row[21]??'',
                'stage_id' => $row[22] ?? '',

            ];
        }

        // Bulk insert into the database
        Lead::insert($dataToInsert);

        return redirect()->back()->with('success', 'CSV data imported successfully.');
    }

    return redirect()->back()->with('error', 'Failed to import CSV data.');
}

}


