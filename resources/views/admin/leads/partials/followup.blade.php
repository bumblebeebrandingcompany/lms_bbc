<table class="table">
    <thead>
        <tr>
            <th>Reference Number</th>
            <th>Campaign Name</th>
            <th>Follow-Up Time</th>
            <th>Follow-Up Date</th>

            <th>Follow-Up By</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($followUps as $followUp)
            <tr>
                <td>{{ $lead->ref_num }}</td>
                <td>{{ $lead->campaign->campaign_name }}</td>
                <td>{{ $followUp->follow_up_time }}</td>
                <td>{{ $followUp->follow_up_date }}</td>

                <td>
                    {{ $followUp->users->representative_name }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>

