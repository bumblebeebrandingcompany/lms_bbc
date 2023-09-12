<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Agency;
use App\Models\Client;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
use Exception;

class UsersController extends Controller
{
    /**
    * All Utils instance.
    *
    */
    protected $util;

    /**
    * Constructor
    *
    */
    public function __construct(Util $util)
    {
        $this->util = $util;
    }

    public function index(Request $request)
    {
        abort_if(!(auth()->user()->is_superadmin || auth()->user()->is_channel_partner_manager), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {

            $projects = $this->util->getProjectDropdown(true);

            $query = User::with(['roles', 'client', 'agency'])
                        ->select(sprintf('%s.*', (new User)->table));

            if(auth()->user()->is_channel_partner_manager) {
                $query->where('users.user_type', 'ChannelPartner');
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = auth()->user()->is_superadmin;
                $editGate      = auth()->user()->is_superadmin;
                $deleteGate    = auth()->user()->is_superadmin;
                $passwordEditGate    = auth()->user()->is_channel_partner_manager;
                $crudRoutePart = 'users';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'passwordEditGate',
                    'row'
                ));
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });

            $table->editColumn('user_type', function ($row) {
                return $row->user_type ? User::USER_TYPE_RADIO[$row->user_type] : '';
            });
            $table->editColumn('contact_number_1', function ($row) {
                return $row->contact_number_1 ? $row->contact_number_1 : '';
            });
            $table->editColumn('website', function ($row) {
                return $row->website ? $row->website : '';
            });
            $table->addColumn('client_name', function ($row) {
                return $row->client ? $row->client->name : '';
            });

            $table->addColumn('agency_name', function ($row) {
                return $row->agency ? $row->agency->name : '';
            });

            $table->addColumn('assigned_projects', function ($row) use($projects) {
                $project_assigned = [];
                if(
                    !empty($row->project_assigned) && !empty($projects)
                ) {
                    foreach($row->project_assigned as $id) {
                        if(isset($projects[$id])) {
                            $project_assigned[] = $projects[$id];
                        }
                    }
                }

                $project_assigned_html = '';
                if(!empty($project_assigned)) {
                    $project_assigned_html = implode(', ', $project_assigned);
                }

                return $project_assigned_html;
            });

            $table->rawColumns(['actions', 'name', 'placeholder', 'client', 'agency', 'assigned_projects']);

            return $table->make(true);
        }

        $clients  = Client::get();
        $agencies = Agency::get();

        return view('admin.users.index', compact('clients', 'agencies'));
    }

    public function create()
    {
        abort_if(!(auth()->user()->is_superadmin || auth()->user()->is_channel_partner_manager), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agencies = Agency::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $projects = [];
        if(auth()->user()->is_channel_partner_manager) {
            $projects = $this->util->getClientProjects(auth()->user()->client_assigned);
        } else if(auth()->user()->is_superadmin) {
            $projects = $this->util->getProjectDropdown(true);
        }
        return view('admin.users.create', compact('agencies', 'clients', 'roles', 'projects'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());

        $user->ref_num = $this->util->generateUserRefNum($user);
        $user->save();

        // $user->roles()->sync($request->input('roles', []));
        
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $clients = Client::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agencies = Agency::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $user->load('roles', 'client', 'agency');

        $projects = $this->util->getProjectDropdown(true);

        return view('admin.users.edit', compact('agencies', 'clients', 'roles', 'user', 'projects'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        // $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles', 'client', 'agency', 'createdByProjects', 'clientProjects');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function editPassword(Request $request, $id)
    {
        abort_if(!auth()->user()->is_channel_partner_manager, Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        if($request->ajax()) {
            $user = User::findOrFail($id);
            return view('admin.users.partials.edit_password')
                ->with(compact('user'));
        }
    }

    public function updatePassword(Request $request, $id)
    {
        abort_if(!auth()->user()->is_channel_partner_manager, Response::HTTP_FORBIDDEN, '403 Forbidden');
        if($request->ajax()) {
            try {
                $user = User::findOrFail($id);
                if(!empty($request->input('password'))) {
                    $user->password = $request->input('password');
                    $user->save();
                }
                $output = [
                    'success' => true,
                    'msg' => 'Success'
                ];
            } catch (\Exception $e) {
                $output = [
                    'success' => false,
                    'msg' => 'Something went wrong.'
                ];
            }
            return $output;
        }
    }
}
