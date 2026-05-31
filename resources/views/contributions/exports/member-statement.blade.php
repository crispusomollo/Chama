<!DOCTYPE html>
<html>
<head>

    <title>Member Statement</title>

    <style>

        body {
            font-family: sans-serif;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f3f4f6;
            text-align: left;
        }

        .header {
            margin-bottom: 20px;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
        }

        .summary {
            margin-top: 20px;
        }

    </style>

</head>
<body>

    <div class="header">

        <div class="title">
            CHAMA MEMBER CONTRIBUTION STATEMENT
        </div>

        <p>
            <strong>Member:</strong>
            {{ $borrower->full_name }}
        </p>

        <p>
            <strong>Contact:</strong>
            {{ $borrower->contact_no }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $borrower->email }}
        </p>

    </div>

    <table>

        <thead>

            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Status</th>
                <th>Period</th>
            </tr>

        </thead>

        <tbody>

        @foreach($contributions as $c)

            <tr>

                <td>
                    {{ $c->contribution_date }}
                </td>

                <td>
                    KES {{ number_format($c->amount) }}
                </td>

                <td>
                    {{ ucfirst($c->type) }}
                </td>

                <td>
                    {{ ucfirst($c->status) }}
                </td>

                <td>
                    {{ $c->period }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <div class="summary">

        <h3>
            Total Contributions:
            KES {{ number_format($totalAmount) }}
        </h3>

    </div>

</body>
</html>
