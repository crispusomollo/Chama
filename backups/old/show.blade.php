<!DOCTYPE html>
<html>
<head>
    <title>Borrower Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Borrower Details</h2>

<div class="card p-3">
    <p><strong>First Name:</strong> {{ $borrower->firstname }}</p>
    <p><strong>Last Name:</strong> {{ $borrower->lastname }}</p>
    <p><strong>Contact:</strong> {{ $borrower->contact_no }}</p>
    <p><strong>Email:</strong> {{ $borrower->email }}</p>
    <p><strong>Address:</strong> {{ $borrower->address }}</p>
    <p><strong>Tax ID:</strong> {{ $borrower->tax_id }}</p>
</div>

<a href="{{ route('borrowers.index') }}" class="btn btn-secondary mt-3">
    Back
</a>

</body>
</html>
