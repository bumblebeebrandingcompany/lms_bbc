<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAgencyRequest;
use App\Http\Requests\StoreAgencyRequest;
use App\Http\Requests\UpdateAgencyRequest;
use App\Models\Agency;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('agency_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Agency::query()->select(sprintf('%s.*', (new Agency)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'agency_show';
                $editGate      = 'agency_edit';
                $deleteGate    = 'agency_delete';
                $crudRoutePart = 'agencies';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('contact_number_1', function ($row) {
                return $row->contact_number_1 ? $row->contact_number_1 : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.agencies.index');
    }

    public function create()
    {
        abort_if(Gate::denies('agency_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.agencies.create');
    }

    public function store(StoreAgencyRequest $request)
    {
        $agency = Agency::create($request->all());

        return redirect()->route('admin.agencies.index');
    }

    public function edit(Agency $agency)
    {
        abort_if(Gate::denies('agency_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(UpdateAgencyRequest $request, Agency $agency)
    {
        $agency->update($request->all());

        return redirect()->route('admin.agencies.index');
    }

    public function show(Agency $agency)
    {
        abort_if(Gate::denies('agency_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agency->load('agencyUsers', 'agencyCampaigns');

        return view('admin.agencies.show', compact('agency'));
    }

    public function destroy(Agency $agency)
    {
        abort_if(Gate::denies('agency_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $agency->delete();

        return back();
    }

    public function massDestroy(MassDestroyAgencyRequest $request)
    {
        $agencies = Agency::find(request('ids'));

        foreach ($agencies as $agency) {
            $agency->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
