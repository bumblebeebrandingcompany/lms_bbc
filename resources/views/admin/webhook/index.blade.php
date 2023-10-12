@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h2>
                @lang('messages.webhook')
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('messages.new_lead')
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="new-lead">
                                    Api to add new lead
                                </label>
                                <input type="text" class="form-control" id="new-lead" 
                                    readonly value="{{route('webhook.store.new.lead')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        @includeIf('admin.webhook.partials.new_lead_log', ['new_leads_history' => $new_leads_history])
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('messages.lead_activities')
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="lead-activity">
                                    Api to add lead activity
                                </label>
                                <input type="text" class="form-control" id="lead-activity" 
                                    readonly value="{{route('webhook.store.lead.activity')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        @includeIf('admin.webhook.partials.lead_activities_log', ['leads_activities_history' => $leads_activities_history])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection