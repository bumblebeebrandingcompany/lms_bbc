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
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
               <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="call_activity">
                                    Api to add call activity
                                </label>
                                <input type="text" class="form-control" id="call_activity" 
                                    readonly value="{{route('webhook.ca.store')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="followup-conducted">
                                    Api to add followup conducted
                                </label>
                                <input type="text" class="form-control" id="followup-conducted" 
                                    readonly value="{{route('webhook.fc.store')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="followup-scheduled">
                                    Api to add followup scheduled
                                </label>
                                <input type="text" class="form-control" id="followup-scheduled" 
                                    readonly value="{{route('webhook.fs.store')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="new-lead">
                                    Api to add new lead
                                </label>
                                <input type="text" class="form-control" id="new-lead" 
                                    readonly value="{{route('webhook.nl.store')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="note-activity">
                                    Api to add note activity
                                </label>
                                <input type="text" class="form-control" id="note-activity" 
                                    readonly value="{{route('webhook.na.store')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="site-visit-conducted">
                                    Api to add site visit conducted
                                </label>
                                <input type="text" class="form-control" id="site-visit-conducted" 
                                    readonly value="{{route('webhook.svc.store')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="site-visit-scheduled">
                                    Api to add site visit scheduled
                                </label>
                                <input type="text" class="form-control" id="site-visit-scheduled" 
                                    readonly value="{{route('webhook.svs.store')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection