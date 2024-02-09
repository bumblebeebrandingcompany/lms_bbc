@extends('layouts.admin')
@section('content')
    @includeIf('admin.leads.partials.header')
    @if($lead_view == 'list')
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @includeIf('admin.leads.partials.lead_table.lead_table')
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('scripts')
@parent
<script>
    $(function () {
       @includeIf('admin.leads.partials.common_lead_js')
    });
</script>
@endsection
