<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Clients;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Clients::query()->select(sprintf('%s.*', (new Clients)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = auth()->user()->is_superadmin;
                $editGate      = auth()->user()->is_superadmin;
                $deleteGate    = auth()->user()->is_superadmin;
                $crudRoutePart = 'clients';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('website', function ($row) {
                return $row->website ? $row->website : '';
            });
            $table->editColumn('contact_number_1', function ($row) {
                return $row->contact_number_1 ? $row->contact_number_1 : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.clients.index');
    }

    public function create()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clients.create');
    }

    public function store(StoreClientRequest $request)
    {
        $client = Clients::create($request->all());

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $client->id]);
        }

        return redirect()->route('admin.clients.index');
    }

    public function edit(Clients $client)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Clients $client)
    {
        $client->update($request->all());

        return redirect()->route('admin.clients.index');
    }

    public function show(Clients $client)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->load('clientProjects', 'clientUsers');

        return view('admin.clients.show', compact('client'));
    }

    public function destroy(Clients $client)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->delete();

        return back();
    }

    public function massDestroy(MassDestroyClientRequest $request)
    {
        $clients = Clients::find(request('ids'));

        foreach ($clients as $client) {
            $client->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Clients();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
