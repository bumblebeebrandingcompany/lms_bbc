<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Promo;
use App\Models\SubSource;
use Illuminate\Http\Request;
use App\Models\Walkin;
use App\Utils\Util;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Source;
use App\Http\Requests\WalkinStoreRequest;
use App\Models\Clients;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
class WalkinController extends Controller
{
    protected $util;

    /**
     * Constructor
     *
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }
    public function index()
    {
        // Get all walkins with their related leads
        $walkins = Walkin::with('leads')->get();
        $user = auth()->user();
        if ($user->is_superadmin || $user->is_client) {
            $walkins = Walkin::with('leads')->get();

        } else {
            $walkins = $walkins->filter(function ($walkin) {
                return $walkin->leads && $walkin->leads->contains('created_by', auth()->id());
            });
        }


        $subsource = SubSource::pluck('name', 'id');

        $clients = Clients::all();


        return view('admin.walkinform.index', compact('walkins', 'clients', ));
    }

    public function create(Request $request)
    {
        if (!(auth()->user()->is_superadmin || auth()->user()->is_front_office)) {
            abort(403, 'Unauthorized.');
        }
        $phone = $request->input('phone');
        $email = $request->input('email');

        $sub_source_id = request()->get('sub_source_id', null);
        $subsources=SubSource::all();
        $leads = Lead::where('phone', $phone)->orWhere('email', $email)->get();

        $client = Clients::all();
        $leads = Lead::all();
        $walkins=Walkin::all();
        return view('admin.walkinform.create', compact( 'leads','walkins','subsources'));
    }


    public function store(WalkinStoreRequest $request)
    {
        try {
            $request->validate([
                'father_name' => 'required|string|max:255',
                'email' => 'required|string',
                'phone' => 'required|string|max:255',
                'sub_source_id' => 'required',
            ]);

            // Check if lead with the same phone number or email already exists
            $existingLead = Lead::where('email', $request->email)
                ->orWhere('phone', $request->phone)
                ->first();

            if ($existingLead) {
                // If lead exists, throw validation exception with custom message
                throw ValidationException::withMessages(['lead_exists' => 'Lead already exists.'])
                    ->errorBag('storeWalkin');
            }

            // Continue with creating a new walkin and lead
            $walkin = Walkin::create([
                'father_name' => $request->input('father_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'sub_source_id' => $request->input('sub_source_id'),
                'secondary_email' => $request->input('secondary_email'),
                'secondary_phone' => $request->input('secondary_phone'),
            ]);

            $lead = Lead::create([
                'walkin_id' => $walkin->id,
                'father_name' => $walkin->father_name,
                'email' => $walkin->email,
                'phone' => $walkin->phone,
                'stage_id' => 11,
                'created_by' => auth()->user()->id,
                'secondary_email' => $request->secondary_email,
                'secondary_phone' => $request->secondary_phone,
                'sub_source_id' => $request->sub_source_id,
            ]);

            $lead->ref_num = $this->util->generateLeadRefNum($lead);
            $lead->save();

            $walkins = Walkin::all();
            $subsource = SubSource::all();

            return view('admin.walkinform.index')->with(compact('walkins', 'subsource'));
        } catch (ValidationException $e) {
            // Catch the validation exception and redirect back with errors and existing lead reference number
          // Inside the catch block
return redirect()->back()->withErrors($e->errors(), 'storeWalkin')
    ->with(['existingLeadRefNum' => $existingLead->ref_num ?? null]);

        }
    }


    public function show($id)
    {
        $walkin = Walkin::findOrFail($id);

        return view('admin.walkinform.show', compact('walkin'));
    }
    public function edit($id)
    {
        $walkinform = Walkin::findOrFail($id);
         $lead=Lead::all();
         $subsources=SubSource::all();
        return view('admin.walkinform.edit', compact('walkinform','lead','subsources'));
    }

    public function update(Request $request, Walkin $walkinform)
    {
        $request->validate([
            'father_name' => 'required|string|max:255',
            'email' => 'required|string',
            'phone' => 'required|string|max:255',
        ]);

        $data = $request->only([
            'father_name',
            'email',
            'phone',
            'additional_email',
            'secondary_phone',
        ]);

        $walkinform->update($data);
        if ($walkinform->leads()->exists()) {
            $lead = $walkinform->leads()->first();
            $lead->update($data);
            $this->util->storeUniqueWebhookFields($lead);
        }

        return redirect()->route('admin.walkinform.index')->with('success', 'Form updated successfully');
    }


    public function getLeadDetailsKeyValuePair($lead_details_arr)
    {
        if (!empty($lead_details_arr)) {
            $lead_details = [];
            foreach ($lead_details_arr as $lead_detail) {
                if (isset($lead_detail['key']) && !empty($lead_detail['key'])) {
                    $lead_details[$lead_detail['key']] = $lead_detail['value'] ?? '';
                }
            }
            return $lead_details;
        }
        return [];
    }
    public function destroy($id)
    {
        $walkinform = Walkin::findOrFail($id);
        $walkinform->delete();
        return redirect()->route('admin.walkinform.index')->with('success', 'Walkin deleted successfully');
    }
    // Inside your WalkinController
public function checkDuplicate(Request $request)
{
    $phoneExists = Walkin::where('phone', $request->input('phone'))->exists();
    $emailExists = Walkin::where('email', $request->input('email'))->exists();

    return response()->json([
        'duplicatePhone' => $phoneExists,
        'duplicateEmail' => $emailExists,
    ]);
}
public function getLeads(Request $request)
{
    $phone = $request->input('phone');
    $email = $request->input('email');

    // Query the database to find leads based on phone or email
    $leads = Lead::where('phone', $phone)->orWhere('email', $email)->get();
    return response()->json($leads);
}


}

