@extends('layouts.kanban')
@section('styles')
<style>
    #filter-container {
        min-height: auto !important;
    }
</style>
@endsection
@section('content_header')
<div class="container-fluid">
    @includeIf('admin.leads.partials.header')
</div>
@endsection
@section('content')
@if($lead_view == 'kanban')
    @includeIf('admin.leads.partials.kanban.kanban')
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