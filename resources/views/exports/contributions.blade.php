<table>
    <thead>
        <tr>
            <th>Member</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
            <th>Type</th>
            <th>Period</th>
        </tr>
    </thead>

    <tbody>
        @foreach($contributions as $c)
            <tr>
                <td>{{ $c->borrower->full_name }}</td>
                <td>{{ $c->amount }}</td>
                <td>{{ $c->contribution_date }}</td>
                <td>{{ $c->status }}</td>
                <td>{{ $c->type }}</td>
                <td>{{ $c->period }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
