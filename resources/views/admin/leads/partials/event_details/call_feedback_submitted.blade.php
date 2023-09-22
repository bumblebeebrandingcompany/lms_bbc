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
                            in_array($label, ['called_on', 'direction', 'duration', 'feedback', 'message', 'notes', 'originator', 'recipient', 'recording', 'status', 'initiated_by'])
                        )
                            <tr>
                                <td>
                                    {{ucfirst(str_replace('_', ' ', $label))}}
                                </td>
                                <td>
                                    @if(!empty($value) && is_array($value))
                                        @if(in_array($label, ['recording']))
                                            @foreach($value as $data)
                                                <audio controls>
                                                    <source src="{{$data}}" type="audio/wav">
                                                    Your browser does not support the audio element.
                                                </audio>
                                                <br>
                                            @endforeach
                                        @else
                                            @foreach($value as $data)
                                                {{$data}} <br>
                                            @endforeach
                                        @endif
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