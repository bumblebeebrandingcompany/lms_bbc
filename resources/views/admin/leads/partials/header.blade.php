<div class="row">
    <div class="col-sm-12 d-flex justify-content-between align-items-center">
        <h2>
            {{ trans('cruds.lead.title_singular') }} {{ trans('global.list') }}
        </h2>
        <div class="cta">
            <div class="btn-group btn-group-toggle mr-2" data-toggle="buttons">
                <label class="btn btn-outline-secondary @if($lead_view == 'kanban') active @endif">
                    <input type="radio" name="toggle_view" class="toggle_view" id="kanban" value="kanban" @if($lead_view == 'kanban') checked @endif>
                    @lang('messages.kanban_view')
                </label>
                <label class="btn btn-outline-secondary @if($lead_view == 'list') active @endif">
                    <input type="radio" name="toggle_view" class="toggle_view" id="list" value="list" @if($lead_view == 'list') checked @endif>
                    @lang('messages.list_view')
                </label>

                {{-- <label class="btn btn-outline-secondary @if($lead_view == 'list') active @endif">
                    <input type="radio" name="toggle_view" class="toggle_view" id="list" value="list" @if($lead_view == 'list') checked @endif>
                    @lang('messages.list_view')
                </label>
                <label class="btn btn-outline-secondary @if($lead_view == 'kanban') active @endif">
                    <input type="radio" name="toggle_view" class="toggle_view" id="kanban" value="kanban" @if($lead_view == 'kanban') checked @endif>
                    @lang('messages.kanban_view')
                </label> --}}
            </div>
            @if(auth()->user()->is_superadmin )
                <a class="btn btn-success float-right" href="{{ route('admin.leads.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.lead.title_singular') }}
                </a>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="row">
                    @if(!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                        <div class="col-md-3">
                            <label for="project_id">
                                @lang('messages.projects')
                            </label>
                            <select class="search form-control" id="project_id">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($projects as $key => $item)
                                    <option value="{{ $item->id }}" @if(isset($filters['project_id']) && $filters['project_id'] == $item->id) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if(!(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                        <div class="col-md-3 campaigns_div">
                            <label for="campaign_id">
                                @lang('messages.campaigns')
                            </label>
                            <select class="search form-control" id="campaign_id">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($campaigns as $key => $item)
                                    <option value="{{ $item->id }}" @if(isset($filters['campaign_id']) && $filters['campaign_id'] == $item->id) selected @endif>{{ $item->campaign_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if(!(auth()->user()->is_agency || auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                        <div class="col-md-3 sources_div">
                            <label for="source_id">
                                Source
                            </label>
                            <select class="search form-control" name="source" id="source_id">
                                <option value>{{ trans('global.all') }}</option>
                                @foreach($sources as $source)
                                    <option value="{{$source->id}}" @if(isset($filters['source']) && $filters['source'] == $item->id) selected @endif>{{ $source->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="added_on">{{ trans('messages.added_on') }}</label>
                            <input class="form-control date_range" type="text" name="date" id="added_on" readonly>
                        </div>
                    @endif
                    <!-- <div class="col-md-3">
                        <label for="leads_status">
                            @lang('messages.status')
                        </label>
                        <select class="search form-control" name="leads_status" id="leads_status">
                            <option value>{{ trans('global.all') }}</option>
                            <option value="new" @if(isset($filters['leads_status']) && $filters['leads_status'] == 'new') selected @endif>New</option>
                            <option value="duplicate" @if(isset($filters['leads_status']) && $filters['leads_status'] == 'duplicate') selected @endif>Duplicate</option>
                        </select>
                    </div> -->
                    <!-- @if(auth()->user()->is_superadmin)
                        <div class="col-md-3 mt-auto mb-2">
                            <div class="form-check">
                                <input class="form-check-input search" type="checkbox" id="no_lead_id" value="1" @if(isset($filters['no_lead_id']) && $filters['no_lead_id'] != 'false') checked @endif>
                                <label for="no_lead_id" class="form-check-label">
                                    @lang('messages.no_lead_id')
                                </label>
                            </div>
                        </div>
                    @endif -->
                    @if($lead_view == 'kanban')
                        <div class="col-md-3">
                            <label></label>
                            <button type="button" class="btn btn-block btn-outline-info" id="filter_leads">
                                @lang('messages.filter_leads')
                            </button>
                        </div>
                    @endif
                    @if(auth()->user()->is_superadmin)
                        <div class="col-md-3">
                            <label></label>
                            <button type="button" class="btn btn-block btn-outline-primary" id="send_bulk_outgoing_webhook">
                                @lang('messages.send_outgoing_webhook')
                            </button>
                        </div>
                    @endif
                    <div class="col-md-12"><hr></div>
                    @if(auth()->user()->is_superadmin)
                        <div class="col-md-3 additional_columns_to_export_div"
                            style="display: none;">
                        </div>
                        <div class="col-md-3 mb-auto mt-auto">
                            <label></label>
                            <button type="button" class="btn btn-block btn-outline-info" id="download_excel">
                                @lang('messages.download_excel')
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
