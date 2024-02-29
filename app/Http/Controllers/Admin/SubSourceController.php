<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPromoRequest;
use App\Http\Requests\UpdateSubSourceRequest;
use App\Http\Requests\StoreSubSourceRequest;
use App\Models\Campaign;
use App\Models\Project;
use App\Models\SubSource;
use App\Models\Source;
use Gate;
use App\Models\Lead;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;

class SubSourceController extends Controller
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
        if(!auth()->user()->is_superadmin && !auth()->user()->is_client  ) {
            abort(403, 'Unauthorized.');
        }

        if ($request->ajax()) {
            $query = SubSource::with(['project', 'campaign','source'])->select(sprintf('%s.*', (new SubSource)->table));
            $__global_clients_filter = $this->util->getGlobalClientsFilter();
            if(!empty($__global_clients_filter)) {
                $project_ids = $this->util->getClientsProjects($__global_clients_filter);
                $campaign_ids = $this->util->getClientsCampaigns($__global_clients_filter);
                $source_ids = $this->util->getClientsCampaigns($__global_clients_filter);

                $query->where(function ($q) use($project_ids, $campaign_ids,$source_ids) {
                    $q->whereIn('subsource.project_id', $project_ids)
                        ->orWhereIn('subsource.campaign_id', $campaign_ids)
                        ->orWhereIn('subsource.source_id',$source_ids)
                        ;
                })->groupBy('subsource.id');
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'promo_show' ;
                $user=auth()->user();
                $editGate      = 'promo_edit'  && $user->is_superadmin;
                $deleteGate    = 'promo_delete' && $user->is_superadmin;
                $crudRoutePart = 'subsource';
                return view('admin.subsource.datatableActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',

                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->addColumn('name', function ($row) {
                return $row->campaign ? $row->campaign->name : '';
            });

            $table->addColumn('name', function ($row) {
                return $row->source ? $row->source->name : '';
            });

            // $table->editColumn('name', function ($row) {
            //     $html =  $row->name ? $row->name : '';
            //     if($row->is_cp_source) {
            //         $html .= "<br>".'<span class="badge badge-pill badge-info">'.__('messages.cp_source').'</span>';
            //     }
            //     return $html;
            // });

            $table->rawColumns(['actions', 'placeholder', 'project', 'campaign', 'name']);
            $promos=SubSource::all();
            return $table->make(true);
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects  = Project::whereIn('id', $project_ids)
                        ->get();

        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->get();
            $sources=Source::all();

        return view('admin.subsource.index', compact('projects', 'campaigns'));
    }

    public function create()
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects = Project::whereIn('id', $project_ids)
                        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
                        $project_id = request()->get('project_id', null);

        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
                        $sources=Source::all() ;
        return view('admin.subsource.create', compact('campaigns', 'projects','sources','project_id'));
    }

    public function store(StoreSubSourceRequest $request)
    {
        $subsource=SubSource::all();
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }
        $input = $request->validated();

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);
        $projects = Project::whereIn('id', $project_ids)
        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

          $campaigns = Campaign::whereIn('id', $campaign_ids)
        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $promo_details = $request->except('_token');
        $subsource = SubSource::create($input);

        return redirect()->route('admin.subsource.index',compact('campaigns', 'projects','subsource'));
    }
    public function edit(SubSource $subsource)
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        $projects = Project::whereIn('id', $project_ids)
                        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
          $source=Source::all();
          $subsource->load('project', 'campaign','source');
        return view('admin.subsource.edit', compact('campaigns', 'projects', 'source','subsource'));
    }

    public function update(UpdateSubSourceRequest $request, SubSource $subsource)
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }

        $promo_details = $request->except(['_method', '_token']);
        $subsource->update($promo_details);

        return redirect()->route('admin.subsource.index');
    }

    public function show(SubSource $subsource)
    {
        if(!auth()->user()->is_superadmin && !auth()->user()->is_client) {
            abort(403, 'Unauthorized.');
        }
        $source=Source::all();
$project=Project::all();
$campaign=Campaign::all();
        $subsource->load('project', 'campaign','source');

        return view('admin.subsource.show', compact('source','project','source','subsource'));
    }

    public function destroy(SubSource $subsource)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subsource->delete();

        return back();
    }

    public function massDestroy(MassDestroyPromoRequest $request)
    {
        if(!auth()->user()->is_superadmin) {
            abort(403, 'Unauthorized.');
        }
        $promos = SubSource::find(request('ids'));
        foreach ($promos as $subsource) {
            $subsource->delete();
        }
        return response(null, Response::HTTP_NO_CONTENT);
    }
    // public function getSource(Request $request)
    // {
    //     if($request->ajax()) {

    //         $query = Source::where('project_id', $request->input('project_id'))
    //                 ->where('campaign_id', $request->input('campaign_id'));

    //         if(auth()->user()->is_channel_partner) {
    //             $assigned_sources = auth()->user()->sources;
    //             $query->whereIn('id', $assigned_sources);
    //         }

    //         $sources = $query->pluck('name', 'id')
    //                     ->toArray();

    //         $sources_arr = [['id' => '', 'text' => __('messages.please_select')]];
    //         if(!empty($sources)) {
    //             foreach ($sources as $id => $text) {
    //                 $sources_arr[] = [
    //                     'id' => $id,
    //                     'text' =>$text
    //                 ];
    //             }
    //         }
    //         return $sources_arr;
    //     }
    // }
    // public function getWebhookDetails($id)
    // {
    //     if (!auth()->user()->is_superadmin) {
    //         abort(403, 'Unauthorized.');
    //     }

    //     $source = Source::with(['project'])->findOrFail($id);

    //     $lead = Lead::where('source_id', $id)->latest()->first();
    //     // $lead=Lead::table('leads')->get()->toArray();
    //     // echo "<pre>";
    //     // print_r($lead);

    //     return view('admin.sources.webhook', compact('source', 'lead'));
    // }


    // public function updatePhoneAndEmailKey(Request $request)
    // {
    //     $source = Source::findOrFail($request->input('source_id'));
    //     $source->email_key = $request->input('email_key');
    //     $source->phone_key = $request->input('phone_key');
    //     $source->name_key = $request->input('name_key');
    //     $source->additional_email_key = $request->input('additional_email_key');
    //     $source->secondary_phone_key = $request->input('secondary_phone_key');
    //     $source->save();

    //     return redirect()->route('admin.sources.webhook', $source->id);
    // }
}
