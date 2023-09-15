<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NoteActivity;
use Carbon\Carbon;
use Exception;
class NoteActivityController extends Controller
{
    public function store(Request $request)
    {
        try {
            //request data
            $fillableFields = [
                'lead_id',
                'lead_first_name',
                'lead_last_name',
                'lead_phone',
                'lead_email',
                'event',
                'payload_id',
                'payload_content',
                'payload_created_at',
                'payload_note_type',
                'payload_updated_at',
            ];

            $requestData = $request->only($fillableFields);
            $additionalDetails = $request->except($fillableFields);

            $requestData['payload_created_at'] = !empty($requestData['payload_created_at']) ? Carbon::parse($requestData['payload_created_at']) : null;
            $requestData['payload_updated_at'] = !empty($requestData['payload_updated_at']) ? Carbon::parse($requestData['payload_updated_at']) : null;
            
            $noteActivity = new NoteActivity();
            $noteActivity->fill($requestData);
            $noteActivity->additional_details = $additionalDetails;
            $noteActivity->save();

            return response()->json(__('messages.success'));
        } catch (Exception $e) {
            $msg = 'File:'.$e->getFile().' | Line:'.$e->getLine().' | Message:'.$e->getMessage();
            \Log::info('Note Activity:- '.$msg);
            return response()->json(__('messages.something_went_wrong')); 
        }
    }
}
