@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-4">Add Borrower</h2>

<form action="{{ route('borrowers.store') }}" method="POST" class="bg-white p-6 rounded shadow">

    @csrf

    <div class="grid grid-cols-2 gap-4">

        <input name="firstname" placeholder="First Name" class="border p-2 rounded" required>

        <input name="middlename" placeholder="Middle Name" class="border p-2 rounded">

        <input name="lastname" placeholder="Last Name" class="border p-2 rounded" required>

        <input name="email" placeholder="Email" class="border p-2 rounded">

        <input name="contact_no" placeholder="Phone Number" class="border p-2 rounded">

        <input name="address" placeholder="Address" class="border p-2 rounded">

        <input name="tax_id" placeholder="Tax ID" class="border p-2 rounded">

    </div>

    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        Save Borrower
    </button>

</form>

@endsection
