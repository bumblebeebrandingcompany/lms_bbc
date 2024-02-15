<div class="row">
    <div class="col-md-12">
        <h4>@lang('messages.log')</h4>
    </div>
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search on new lead..." id="new_lead_response_search">
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
                        <th>@lang('messages.webhook_response')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($new_leads_history as $new_lead)
                        <tr>
                            <td>
                                {{@format_datetime($new_lead->created_at)}}
                            </td>
                            <td>
                                @if(!empty($new_lead->lead_event_webhook_response))
                                    <div class="row">
                                        <div class="col-md-12 text-nowrap">
                                            <!-- {{json_encode($new_lead->lead_event_webhook_response)}} -->
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">
                                No log found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(!empty($new_leads_history->links()))
        <div class="col-md-12 text-right mb-3">
            {{ $new_leads_history->links() }}
        </div>
    @endif
</div>