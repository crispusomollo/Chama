<h2>Contribution Report</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Member</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
            <th>Type</th>
        </tr>
    </thead>

    <tbody>
        @foreach($contributions as $c)
            <tr>
                <td>{{ $c->borrower->full_name }}</td>
                <td>{{ number_format($c->amount) }}</td>
                <td>{{ $c->contribution_date }}</td>
                <td>{{ strtoupper($c->status) }}</td>
                <td>{{ ucfirst($c->type) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
