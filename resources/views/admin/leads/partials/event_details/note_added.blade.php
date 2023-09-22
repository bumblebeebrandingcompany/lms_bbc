@php
    $payload = $event->webhook_data['payload'] ?? [];
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
                    @forelse($payload as $label => $value)
                        @if(
                            !empty($label) && 
                            !empty($value) &&
                            in_array($label, ['content', 'note_type'])
                        )
                            <tr>
                                <td>
                                    {{ucfirst(str_replace('_', ' ', $label))}}
                                </td>
                                <td>
                                    @if(!empty($value) && is_array($value))
                                        @foreach($value as $data)
                                            {{$data}} <br>
                                        @endforeach
                                    @else
                                        {{$value ?? ''}}
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