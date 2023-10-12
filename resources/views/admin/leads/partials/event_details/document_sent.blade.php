@php
    $webhook_data = $event->webhook_data ?? [];
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

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            @lang('messages.key')
                        </th>
                        <th>
                            @lang('messages.value')
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($webhook_data as $label => $value)
                        @if(
                            !empty($label) && 
                            !empty($value)
                        )
                            <tr>
                                <td>
                                    {{ucfirst(str_replace('_', ' ', $label))}}
                                </td>
                                <td>
                                    @if($label == 'datetime')
                                        {{@format_datetime($value)}}
                                    @else
                                        {!! nl2br($value ?? '')!!}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">
                                No data found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>