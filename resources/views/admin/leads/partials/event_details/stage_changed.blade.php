    @php
        $payload = json_decode($item->payload, true);
        $notes = $payload['sitevisit'] ?? null;
    @endphp


    @if ($notes)
        <div class="row">
            <div class="col-md-6">
                <strong>Notes:</strong> {{ $notes['notes'] }} <br>
            </div>
        </div>
    @endif
