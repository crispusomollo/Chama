@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-4">Add Borrower</h2>

<form action="{{ route('borrowers.store') }}" method="POST" class="bg-white p-6 rounded shadow">
    @csrf

    <div class="mb-4">
        <label class="block">Name</label>
        <input name="name" class="w-full border p-2 rounded" required>
    </div>

    <div class="mb-4">
        <label class="block">Email</label>
        <input name="email" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label class="block">Phone</label>
        <input name="phone" class="w-full border p-2 rounded">
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">
        Save Borrower
    </button>
</form>

@endsection
