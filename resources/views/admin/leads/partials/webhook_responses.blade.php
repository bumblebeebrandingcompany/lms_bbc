<div class="row">
    <div class="col-md-12">
        @if(
            !(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager) &&
            !empty($lead->webhook_response)
        )
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap">
                    <tbody>
                        @foreach($lead->webhook_response as $response)
                            <tr>
                                <th>
                                    {{$loop->iteration}}
                                </th>
                                <td>
                                    @if(is_string($response))
                                        {{$response}}
                                    @else
                                    @if(
                                        !empty($response) &&
                                        array_key_exists('input', $response) &&
                                        array_key_exists('response', $response)
                                    )
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped text-nowrap">
                                                    <thead>
                                                        <th>
                                                            Input
                                                        </th>
                                                        <th>
                                                            Output
                                                        </th>
                                                    </thead>
                                                    <tbody>
                                                        <td>
                                                            <pre>
                                                                {!! print_r($response['input'], true) !!}
                                                            </pre>
                                                        </td>
                                                        <td>
                                                            <pre>
                                                                {!! print_r($response['response'], true) !!}
                                                            </pre>
                                                        </td>
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <pre>
                                                {!! print_r($response, true) !!}
                                            </pre>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="callout callout-warning">
                <h5>No, webhook response found.</h5>
            </div>
        @endif
    </div>
</div>
