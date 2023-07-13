<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCampaignRequest;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
use App\Models\Agency;
use App\Models\Campaign;
use App\Models\Project;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('campaign_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Campaign::with(['project', 'agency'])->select(sprintf('%s.*', (new Campaign)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'campaign_show';
                $editGate      = 'campaign_edit';
                $deleteGate    = 'campaign_delete';
                $crudRoutePart = 'campaigns';

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
            $table->editColumn('campaign_name', function ($row) {
                return $row->campaign_name ? $row->campaign_name : '';
            });

            $table->editColumn('source', function ($row) {
                return $row->source ? Campaign::SOURCE_SELECT[$row->source] : '';
            });
            $table->addColumn('project_name', function ($row) {
                return $row->project ? $row->project->name : '';
            });

            $table->addColumn('agency_name', function ($row) {
                return $row->agency ? $row->agency->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'project', 'agency']);

            return $table->make(true);
        }

        $projects = Project::get();
        $agencies = Agency::get();

        return view('admin.campaigns.index', compact('projects', 'agencies'));
    }

    public function create()
    {
        abort_if(Gate::denies('campaign_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agencies = Agency::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.campaigns.create', compact('agencies', 'projects'));
    }

    public function store(StoreCampaignRequest $request)
    {
        $campaign = Campaign::create($request->all());

        return redirect()->route('admin.campaigns.index');
    }

    public function edit(Campaign $campaign)
    {
        abort_if(Gate::denies('campaign_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $agencies = Agency::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $campaign->load('project', 'agency');

        return view('admin.campaigns.edit', compact('agencies', 'campaign', 'projects'));
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        $campaign->update($request->all());

        return redirect()->route('admin.campaigns.index');
    }

    public function show(Campaign $campaign)
    {
        abort_if(Gate::denies('campaign_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $campaign->load('project', 'agency', 'campaignLeads');

        return view('admin.campaigns.show', compact('campaign'));
    }

    public function destroy(Campaign $campaign)
    {
        abort_if(Gate::denies('campaign_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $campaign->delete();

        return back();
    }

    public function massDestroy(MassDestroyCampaignRequest $request)
    {
        $campaigns = Campaign::find(request('ids'));

        foreach ($campaigns as $campaign) {
            $campaign->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
