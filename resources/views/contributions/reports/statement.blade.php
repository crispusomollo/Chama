<!DOCTYPE html>
<html>
<head>
    <title>Member Statement</title>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>

    <style>
        body {
            font-family: Arial;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
        }

        th {
            background: #f3f4f6;
        }
    </style>
</head>
<body>

<h2>Contribution Statement</h2>

<p>
    Generated:
    {{ now()->format('d M Y H:i') }}
</p>

<table>

    <thead>
        <tr>
            <th>Member</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    @foreach($contributions as $c)

        <tr>
            <td>{{ $c->borrower->full_name }}</td>
            <td>KES {{ number_format($c->amount) }}</td>
            <td>{{ $c->contribution_date }}</td>
            <td>{{ strtoupper($c->status) }}</td>
        </tr>

    @endforeach

    </tbody>

</table>

<h3>
    Total:
    KES {{ number_format($total) }}
</h3>

</body>
</html>
