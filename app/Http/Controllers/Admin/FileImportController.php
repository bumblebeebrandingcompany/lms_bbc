<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\LeadImport;

class FileImportController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.import.index');
    }

    // public function store(Request $request)
    // {
    //     $file = $request->file('csv_file');

    //     // Check if a file was uploaded
    //     if ($file) {
    //         // Get the file path
    //         $filePath = $file->getRealPath();

    //         // Read the file contents
    //         $fileContents = file($filePath);

    //         foreach ($fileContents as $line) {
    //             $data = str_getcsv($line);

    //             try {
    //                 FileImport::create([
    //                     'id' => $data[0],
    //                     'mother_name' => $data[1],
    //                     'father_name' => $data[2],
    //                     // Add more fields as needed
    //                 ]);
    //             } catch (\Exception $e) {
    //                 // Log or handle the exception
    //                 \Log::error("Error importing CSV row: " . implode(',', $data));
    //             }
    //         }

    //         return redirect()->back()->with('success', 'CSV file imported successfully.');
    //     } else {
    //         return redirect()->back()->with('error', 'No file uploaded.');
    //     }
    // }


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
                    'father_name' => $row[0], // Assuming first_name is in the first column
                    'mother_name' => $row[1],  // Assuming last_name is in the second column
                ];
            }

            // Bulk insert into the database
            FileImport::insert($dataToInsert);

            return redirect()->back()->with('success', 'CSV data imported successfully.');
        }

        return redirect()->back()->with('error', 'Failed to import CSV data.');
    }

    // private function processCsv($filePath, Request $request)
    // {
    //     $file = storage_path("app/public/{$filePath}");
    //     $handle = fopen($file, 'r');

    //     // Assuming the first row contains column headers
    //     $headers = fgetcsv($handle);

    //     // Loop through the remaining rows and insert into the database
    //     while (($row = fgetcsv($handle)) !== false) {
    //         $data = array_combine($headers, $row);
    //         dd($data);
    //         try {
    //             // Insert data into your database table
    //             $import = new FileImport;
    //             $import->mother_name = $data['mother_name'];
    //             $import->father_name = $data['father_name'];
    //             $import->save();

    //         } catch (\Exception $e) {
    //             // Log or print the exception message
    //             dd($e->getMessage());
    //         }

    //     }

    //     fclose($handle);
    // }

}
