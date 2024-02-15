@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>@lang('messages.webhook')</h2>
        </div>
    </div>

    <div class="col-md-12 mb-3">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">@lang('messages.lead_activities')</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <form action="{{ route('admin.lead-activity.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search on lead activities..." id="lead_activities_response_search">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary">@lang('messages.search')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
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
                                    @forelse($leadactivities as $lead_activity)
                                        <tr>
                                            <td>{{ @format_datetime($lead_activity->created_at) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $lead_activity->activity_type)) }}</td>
                                            <td>
                                                @if(!empty($lead_activity->payload))
                                                    <div class="row">
                                                        <div class="col-md-12 text-nowrap">
                                                            {{ ($lead_activity->payload) }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No log found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @if(!empty($leadactivities->links()))
                    <div class="col-md-12 text-right mb-3">
                        {{ $leadactivities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
