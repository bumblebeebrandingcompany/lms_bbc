<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;

class FilesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Check if both 'first_name' and 'last_name' keys exist in the $row array
        if(isset($row['first_name']) && isset($row['last_name'])) {
            // Create a new FileImport model instance with the provided data
            $fileImport = new Lead([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
            ]);

            // Return the model instance
            return $fileImport;
        } else {

            return null;
        }
    }

}
