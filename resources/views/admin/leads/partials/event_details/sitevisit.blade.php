    @php
        $payload = json_decode($item->payload, true);
        $sitevisit = $payload['sitevisit'] ?? null;
    @endphp

    @if ($sitevisit)
        <div class="row">
            <div class="col-md-6">
                <strong>Notes:</strong> {{ $sitevisit['notes'] ?? 'N/A' }} <br>
                <strong>Date:</strong> {{ $sitevisit['visit_date'] ?? 'N/A' }} <br>
                <strong>Time:</strong> {{ $sitevisit['visit_time'] ?? 'N/A' }} <br>
                <!-- Display other site visit properties as needed -->
            </div>
        </div>
    @else
        <!-- If sitevisit property is not present or null -->
        <div class="row">
            <div class="col-md-6">
                No site visit information available.
            </div>
        </div>
    @endif
