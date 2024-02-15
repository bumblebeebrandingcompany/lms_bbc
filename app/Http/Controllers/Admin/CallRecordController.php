<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\CallRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class CallRecordController extends Controller
{
    public function index()
    {
        $lead = Lead::all();
        $agencies = User::all();
        $campaigns = Campaign::all();
        $callRecords = CallRecord::all();
        $apiKey = config('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNjk4NjY0NTAxLCJleHAiOjE2OTg2NjgxMDEsIm5iZiI6MTY5ODY2NDUwMSwianRpIjoiaFBDRUIwblllUjBjU2N2MCIsInN1YiI6IjE3MDQ0MCJ9.V_qQ_Vtm9d2ojWyqR1ZBfxjQRt2JJnz3YHXgXJ3WIxQ');
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxNzA0NDAiLCJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL3Rva2VuL2dlbmVyYXRlIiwiaWF0IjoxNjk4NjYxMjYwLCJleHAiOjE5OTg2NjEyNjAsIm5iZiI6MTY5ODY2MTI2MCwianRpIjoiTWtYY0h0OXlpNG5Ea2FuaSJ9.L23vhUJ0UIGc3nffLeMK0NMczroLgwwkECFnaCaY-A8';
        $apiUrl = 'https://api.servetel.in/v1/call/records';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get($apiUrl);

        if ($response->successful()) {
            $data = $response->json();
        } else {
            $errorResponse = $response->json();
        }

        $itemsPerPage = request('perPage', 10);

        $callRecords = CallRecord::paginate($itemsPerPage);
       $data = $response->json();
        return view('admin.callog.index', compact('agencies', 'campaigns', 'lead', 'callRecords', 'data'));
    }
    public function show($id)
    {
        // Retrieve the call record by ID
        $callRecord = CallRecord::find($id);

        // Check if the call record exists
        if (!$callRecord) {
            abort(404, 'Call Record not found');
        }

        // Decode the JSON string
        $callFlowJson = $callRecord->call_flow;
        $callFlow = json_decode($callFlowJson, true);

        // Pass the call flow data to the view
        return view('admin.callog.show', compact('callFlow', 'callRecord'));
    }
    public function store(Request $request)
    {
        // Validate incoming webhook request
        $request->validate([
            // Add necessary validation rules based on Servetel webhook payload
        ]);
        // Process Servetel webhook data
        $webhookData = $request->all();

        if (isset($webhookData['recording_url'], $webhookData['client_number'], $webhookData['date'], $webhookData['time'], $webhookData['answered_seconds'], $webhookData['status'])) {
            $recordingUrl = $webhookData['recording_url'];
            $audioData = file_get_contents($recordingUrl);

            $calledBy = $webhookData['client_number'] ?? null;
            $lead = Lead::where('phone', $calledBy)->first();

            if ($calledBy !== null) {
                $fileName = 'audio_' . time() . '.mp3';
                $path = 'audio/' . $fileName;
                $absolutePath = public_path($path);

                File::makeDirectory(dirname($absolutePath), 0755, true, true);

                if (file_put_contents($absolutePath, $audioData) !== false) {
                    CallRecord::create([
                        'lead_id' => $lead->id ?? 21,
                        'client_number' => $webhookData['client_number'],
                        'called_on' => $webhookData['date'],
                        'call_on_time' => $webhookData['time'],
                        'call_duration' => $webhookData['answered_seconds'],
                        'call_recordings' => $path,
                        'status' => $webhookData['status'],
                        'direction' => $webhookData['direction'],
                        'description' => $webhookData['description'],
                        'call_id' => $webhookData['call_id'],
                        'did number' => $webhookData['did_number'],
                        'call_flow' => json_encode($webhookData['call_flow']),
                    ]);
                } else {
                    // Log or handle the case where file_put_contents fails
                }
            } else {
                // Log or handle the case where 'called_by' is null
            }
        } else {
            // Log or handle the case where required keys are missing in the webhook data
        }

        return response()->json(['success' => true]); // Respond to the webhook request
    }
}
