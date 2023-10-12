@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row mb-2">
        <div class="col-sm-12">
            <h2>
                @lang('messages.document_logs')
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                @lang('messages.lead')
                                            </th>
                                            <th>
                                                @lang('messages.document')
                                            </th>
                                            <th>
                                                @lang('messages.note')
                                            </th>
                                            <th>
                                                @lang('messages.status')
                                            </th>
                                            <th>
                                                @lang('messages.sent_by')
                                            </th>
                                            <th>
                                                @lang('messages.sent_at')
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activities as $activity)
                                        <tr>
                                                <td>
                                                    @if(!empty($activity->lead))
                                                        <strong>{{$activity->lead->name ?? ''}}</strong>
                                                    @endif
                                                    @if(!empty($activity->lead->ref_num))
                                                        (<code><small>{{$activity->lead->ref_num ?? ''}}</small></code>)
                                                    @endif
                                                    @if(!empty($activity->lead) && (!empty($activity->lead->email) || !empty($activity->lead->additional_email)))
                                                        <br>
                                                        {{$activity->lead->email ?? ''}}
                                                        @if(!empty($activity->lead->additional_email))
                                                            @if(!empty($activity->lead->email))
                                                                {{'/'}}
                                                            @endif
                                                            {{$activity->lead->additional_email}}
                                                        @endif
                                                    @endif
                                                </td>
                                                @php
                                                    $webhook_data = $activity->webhook_data ?? [];
                                                    if(!empty($webhook_data['sent_by'])) {
                                                        $user = \App\Models\User::find($webhook_data['sent_by']);
                                                        $webhook_data['sent_by'] = !empty($user) ? $user->name : '';
                                                    }
                                                    if(!empty($webhook_data['document_id'])) {
                                                        $document = \App\Models\Document::find($webhook_data['document_id']);
                                                        $webhook_data['document'] = !empty($document) ? $document->title : '';
                                                        if(!empty($document)) {
                                                            $util = new App\Utils\Util();
                                                            $url = $util->generateGuestDocumentViewUrl($document->id);
                                                            $webhook_data['document'] .= '<br><br><a class="btn btn-sm btn-outline-dark" target="_blank" href="'.$url.'">View </a>';
                                                        }
                                                        unset($webhook_data['document_id']);
                                                    }
                                                    if(!empty($webhook_data['document_id'])) {
                                                        $document = \App\Models\Document::find($webhook_data['document_id']);
                                                        $webhook_data['document'] = !empty($document) ? $document->title : '';
                                                        unset($webhook_data['document_id']);
                                                    }
                                                @endphp
                                                <td>
                                                    {!!$webhook_data['document'] ?? ''!!}
                                                </td>
                                                <td>{!!nl2br($webhook_data['note'] ?? '')!!}</td>
                                                <td>
                                                    {{ucfirst($webhook_data['status'] ?? '')}}
                                                </td>
                                                <td>
                                                    {{$webhook_data['sent_by'] ?? ''}}
                                                </td>
                                                <td>
                                                    @if(!empty($webhook_data['datetime']))
                                                        {{@format_datetime($webhook_data['datetime'])}}
                                                    @endif
                                                </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    No log found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if(!empty($activities->links()))
                            <div class="col-md-12 text-right mb-3">
                                {{ $activities->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection