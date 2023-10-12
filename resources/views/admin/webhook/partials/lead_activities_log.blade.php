<div class="row">
    <div class="col-md-12">
        <h4>@lang('messages.log')</h4>
    </div>
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search on lead activities..." id="lead_activities_response_search">
                <div class="input-group-append">
                    <button type="button" class="btn btn-secondary search_data">
                        @lang('messages.search')
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered text-nowrap">
                <thead>
                    <tr>
                        <th>@lang('messages.created_at')</th>
                        <th>@lang('messages.event_type')</th>
                        <th>@lang('messages.webhook_response')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads_activities_history as $lead_activity)
                        <tr>
                            <td>
                                {{@format_datetime($lead_activity->created_at)}}
                            </td>
                            <td>
                                {{ucfirst(str_replace('_', ' ', $lead_activity->event_type))}}
                            </td>
                            <td>
                                <!-- @if(!empty($lead_activity->webhook_data))
                                    <div class="row">
                                        <div class="col-md-12 text-nowrap">
                                            {{json_encode($lead_activity->webhook_data)}}
                                        </div>
                                    </div>
                                @endif -->
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">
                                No log found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(!empty($leads_activities_history->links()))
        <div class="col-md-12 text-right mb-3">
            {{ $leads_activities_history->links() }}
        </div>
    @endif
</div>