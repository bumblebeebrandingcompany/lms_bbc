<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLeadRequest;
use App\Http\Requests\StoreLeadRequest;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Campaign;
use App\Models\Lead;
use App\Models\Project;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\Util;
class LeadsController extends Controller
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
        $project_ids = $this->util->getUserProjects(auth()->user());
        $campaign_ids = $this->util->getCampaigns(auth()->user(), $project_ids);

        if ($request->ajax()) {

            $user = auth()->user();

            $query = Lead::with(['project', 'campaign'])->select(sprintf('%s.*', (new Lead)->table));

            $query = $query->where(function ($q) use($project_ids, $campaign_ids) {
                        $q->whereIn('project_id', $project_ids)
                            ->orWhereIn('campaign_id', $campaign_ids);
                    })->groupBy('id');
            
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) use($user) {
                $viewGate      = true;
                $editGate      = true;
                $deleteGate    = $user->is_superadmin;
                $crudRoutePart = 'leads';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->addColumn('campaign_campaign_name', function ($row) {
                return $row->campaign ? $row->campaign->campaign_name : '';
            });

            $table->editColumn('lead_details', function ($row) {
                $html = '';
                if(!empty($row->lead_details)) {
                    foreach ($row->lead_details as $key => $value) {
                        $html .= $key.': '.$value.'<br>';
                    }
                }
                return  $html;
            });

            $table->rawColumns(['actions', 'placeholder', 'project', 'campaign', 'lead_details']);

            return $table->make(true);
        }

        $projects  = Project::whereIn('id', $project_ids)
                        ->get();
        $campaigns = Campaign::whereIn('id', $campaign_ids)
                        ->get();

        return view('admin.leads.index', compact('projects', 'campaigns'));
    }

    public function create()
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.leads.create', compact('campaigns', 'projects'));
    }

    public function store(StoreLeadRequest $request)
    {
        $lead = Lead::create($request->all());

        return redirect()->route('admin.leads.index');
    }

    public function edit(Lead $lead)
    {
        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaigns = Campaign::pluck('campaign_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $lead->load('project', 'campaign');

        return view('admin.leads.edit', compact('campaigns', 'lead', 'projects'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $input = $request->except(['_method', '_token']);
        $input['lead_details'] = (!empty($input['lead_details']) && is_string($input['lead_details'])) ? json_decode($input['lead_details'], true) : [];

        $lead->update($input);

        return redirect()->route('admin.leads.index');
    }

    public function show(Lead $lead)
    {
        $lead->load('project', 'campaign');

        return view('admin.leads.show', compact('lead'));
    }

    public function destroy(Lead $lead)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lead->delete();

        return back();
    }

    public function massDestroy(MassDestroyLeadRequest $request)
    {
        abort_if(!auth()->user()->is_superadmin, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $leads = Lead::find(request('ids'));

        foreach ($leads as $lead) {
            $lead->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
