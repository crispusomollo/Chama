<table>

    <thead>

        <tr>
            <th colspan="6">
                CHAMA CONTRIBUTION REPORT
            </th>
        </tr>

        <tr>
            <th>Member</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Type</th>
            <th>Date</th>
            <th>Period</th>
        </tr>

    </thead>

    <tbody>

    @foreach($contributions as $c)

        <tr>

            <td>
                {{ $c->borrower->full_name }}
            </td>

            <td>
                {{ $c->amount }}
            </td>

            <td>
                {{ $c->status }}
            </td>

            <td>
                {{ $c->type }}
            </td>

            <td>
                {{ $c->contribution_date }}
            </td>

            <td>
                {{ $c->period }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>
