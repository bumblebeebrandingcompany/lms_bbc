@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-sm-12 d-flex align-items-center justify-content-between">
            <h2>
                Lead Profile  <small>{{ $lead->name ? ' - ' .$lead->name : '' }}</small>
            </h2>
            <a class="btn btn-default float-right" href="{{ route('admin.leads.index') }}">
                <i class="fas fa-chevron-left"></i>
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="lead-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="lead-details-tab" data-toggle="pill" href="#lead-details" role="tab" aria-controls="lead-details" aria-selected="true">
                            <i class="fas fa-th-list"></i>
                            @lang('messages.details')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lead-timeline-tab" data-toggle="pill" href="#lead-timeline" role="tab" aria-controls="lead-timeline" aria-selected="false">
                            <i class="fas fa-history"></i>
                            @lang('messages.profile')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lead-documents-tab" data-toggle="pill" href="#lead-documents" role="tab" aria-controls="lead-documents" aria-selected="false">
                            <i class="fas fa-question-circle"></i>
                            @lang('messages.documents')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="lead-webhook_response-tab" data-toggle="pill" href="#lead-webhook_response" role="tab" aria-controls="lead-webhook_response" aria-selected="false">
                            <i class="fas fa-reply"></i>
                            @lang('messages.webhook_response')
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="lead-tabContent">
                    <div class="tab-pane fade active show" id="lead-details" role="tabpanel" aria-labelledby="lead-details-tab">
                        @includeIf('admin.leads.partials.details')
                    </div>
                    <div class="tab-pane fade" id="lead-timeline" role="tabpanel" aria-labelledby="lead-timeline-tab">
                        @includeIf('admin.leads.partials.timeline')
                    </div>
                    <div class="tab-pane fade" id="lead-documents" role="tabpanel" aria-labelledby="lead-documents-tab">
                        @includeIf('admin.leads.partials.documents')
                    </div>
                    <div class="tab-pane fade" id="lead-webhook_response" role="tabpanel" aria-labelledby="lead-webhook_response-tab">
                        @includeIf('admin.leads.partials.webhook_responses')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $(document).on('click', '.send_doc_to_lead', function() {
            const url = $(this).attr('data-href');
            const btn = $(this);
            btn.attr('disabled', true);
            $.ajax({
                method:"GET",
                url: url,
                dataType: "json",
                success: function(response) {
                    btn.attr('disabled', false);
                    if(response.success) {
                        alert(response.msg);
                    } else{
                        alert(response.msg);
                    }
                }
            });
        });

        //search in docs
        if($("#document_accordion").length) {
            const faqList = new List('document_list', {
                valueNames: ['faq_question', 'faq_answer']
            });
            
            $('#doc_search_input').on('keyup', function() {
                const search_term = $(this).val();
                faqList.search(search_term);
            });
        }
    });
</script>
@endsection