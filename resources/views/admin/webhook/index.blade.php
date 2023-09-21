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
                                <label for="new-lead">
                                    Api to add new lead
                                </label>
                                <input type="text" class="form-control" id="new-lead" 
                                    readonly value="{{route('webhook.store.new.lead')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="lead-activity">
                                    Api to add lead activity
                                </label>
                                <input type="text" class="form-control" id="lead-activity" 
                                    readonly value="{{route('webhook.store.lead.activity')}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection