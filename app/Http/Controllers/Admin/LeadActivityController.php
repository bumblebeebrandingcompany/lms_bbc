<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAgencyRequest;
use App\Http\Requests\StoreAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Models\Agency;
use App\Models\LeadTimeline;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LeadActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LeadTimeline::query();

        // Check if search parameter is present and not empty
        if ($request->has('search') && $request->input('search') !== '') {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('activity_type', 'like', '%' . $searchTerm . '%')
                    ->orWhere('payload', 'like', '%' . $searchTerm . '%');
            });
        }

        // Paginate the results
        $leadactivities = $query->paginate(10);

        // Check if the request is a refresh (no query parameters)
        if (!$request->query()) {
            // If it's a refresh, clear the search parameter
            $leadactivities->appends(['search' => null]);
        }

        return view('admin.leadactivity.index', compact('leadactivities'));
    }

}
