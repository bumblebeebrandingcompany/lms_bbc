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
        $data = $response->json();
        return view('admin.callog.index', compact('agencies', 'campaigns', 'lead', 'callRecords'), ['data' => $data]);
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
        $apiKey = config('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNjk4NjY0NTAxLCJleHAiOjE2OTg2NjgxMDEsIm5iZiI6MTY5ODY2NDUwMSwianRpIjoiaFBDRUIwblllUjBjU2N2MCIsInN1YiI6IjE3MDQ0MCJ9.V_qQ_Vtm9d2ojWyqR1ZBfxjQRt2JJnz3YHXgXJ3WIxQ');
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIxNzA0NDAiLCJpc3MiOiJodHRwczovL2N1c3RvbWVyLnNlcnZldGVsLmluL3Rva2VuL2dlbmVyYXRlIiwiaWF0IjoxNjk4NjYxMjYwLCJleHAiOjE5OTg2NjEyNjAsIm5iZiI6MTY5ODY2MTI2MCwianRpIjoiTWtYY0h0OXlpNG5Ea2FuaSJ9.L23vhUJ0UIGc3nffLeMK0NMczroLgwwkECFnaCaY-A8';
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->get('https://api.servetel.in/v1/call/records');

        $callRecords = $response->json();

        if (isset($callRecords['results']) && is_array($callRecords['results'])) {
            foreach ($callRecords['results'] as $record) {
                if (isset($record['recording_url'], $record['client_number'], $record['date'], $record['time'], $record['answered_seconds'], $record['status'])) {
                    $recordingUrl = $record['recording_url'];
                    $audioData = file_get_contents($recordingUrl);

                    $calledBy = $record['client_number'] ?? null;
                    $lead = Lead::where('phone', $calledBy)->first();

                    if ($calledBy !== null) {
                        $fileName = 'audio_' . time() . '.mp3';
                        $path = 'audio/' . $fileName;
                        $absolutePath = public_path($path);

                        File::makeDirectory(dirname($absolutePath), 0755, true, true);

                        if (file_put_contents($absolutePath, $audioData) !== false) {
                            CallRecord::create([
                                'lead_id' => $lead->id ?? 21,
                                'client_number' => $record['client_number'],
                                'called_on' => $record['date'],
                                'call_on_time' => $record['time'],
                                'call_duration' => $record['answered_seconds'],
                                'call_recordings' => $path,
                                'status' => $record['status'],
                                'direction' => $record['direction'],
                                'description' => $record['description'],
                                'call_id' => $record['call_id'],
                                'did number' => $record['did_number'],
                                'call_flow' => json_encode($record['call_flow']),
                            ]);
                        } else {
                            // Log or handle the case where file_put_contents fails
                        }
                    } else {
                        // Log or handle the case where 'called_by' is null
                    }
                } else {
                    // Log or handle the case where required keys are missing in the record
                }
            }
        } else {
            // Log or handle the case where 'results' key is missing in the API response
        }

        return redirect()->route('admin.callog.store')->with('success', 'Call records stored successfully.');
    }

}
