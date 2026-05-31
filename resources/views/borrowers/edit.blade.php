<h2>Edit Borrower</h2>

<form method="POST" action="{{ route('borrowers.update', $borrower->id) }}">
    @csrf
    @method('PUT')

    <input name="firstname"
           value="{{ $borrower->firstname }}"
           class="form-control mb-2">

    <input name="lastname"
           value="{{ $borrower->lastname }}"
           class="form-control mb-2">

    <input name="contact_no"
           value="{{ $borrower->contact_no }}"
           class="form-control mb-2">

    <input name="email"
           value="{{ $borrower->email }}"
           class="form-control mb-2">

    <textarea name="address"
              class="form-control mb-2">{{ $borrower->address }}</textarea>

    <input name="tax_id"
           value="{{ $borrower->tax_id }}"
           class="form-control mb-2">

    <button class="btn btn-primary">
        Update
    </button>
</form>
