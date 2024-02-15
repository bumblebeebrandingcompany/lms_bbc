@foreach ($timelineItems as $item)
    @php
        $payload = json_decode($item->payload, true);
        $sitevisit = $payload['sitevisit'] ?? null;
    @endphp


    @if ($sitevisit)
        <div class="row">
            <div class="col-md-6">
                <strong>Notes:</strong> {{ $sitevisit['notes'] }} <br>
                <strong>Date:</strong> {{ $sitevisit['follow_up_date'] }} <br>
                <strong>Time:</strong> {{ $sitevisit['follow_up_time'] }} <br>
                <!-- Continue displaying other site visit properties as needed -->
            </div>
        </div>
    @endif
@endforeach
