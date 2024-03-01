@if (auth()->user()->is_superadmin || auth()->user()->is_presales)
    <h3 class="card-title"> Lead ID: {{ $lead->id }}</h3>
    <br>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    {{-- <th>Campaign Name</th> --}}
                    <th>Follow-Up Time</th>
                    <th>Follow-Up Date</th>
                    <th>Notes</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($followUps->where('stage_id', 9) as $followUp)
                    <tr>
                        {{-- <td>{{ $lead->promo->campaign->name }}</td> --}}
                        <td>{{ $followUp->followup_time }}</td>
                        <td>{{ $followUp->followup_date }}</td>
                        <td>{{ $followUp->notes }}</td>
                        <td>{{ $followUp->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@if (auth()->user()->is_admissionteam)
<h3 class="card-title"> Lead ID: {{ $lead->id }}</h3>
    <br>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Follow-Up Time</th>
                    <th>Follow-Up Date</th>
                    <th>Notes</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($followUps->where('stage_id', 28) as $followUp)
                    <tr>
                        <td>{{ $lead->campaign->name }}</td>
                        <td>{{ $followUp->followup_time }}</td>
                        <td>{{ $followUp->followup_date }}</td>
                        <td>{{ $followUp->notes }}</td>
                        <td>{{ $followUp->created_at }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endif
