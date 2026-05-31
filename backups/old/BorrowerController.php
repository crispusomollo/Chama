<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
	public function index()
    {

        $borrowers = Borrower::latest()->get();
        return view('borrowers.index', compact('borrowers'));
    }

    public function create()
    {
        return view('borrowers.create');
    }

    public function store(Request $request)
    {
        Borrower::create($request->all());

	return redirect()
	   ->route('borrowers.index')
           ->with('success', 'Borrower created successfully');
    }

    public function edit(Borrower $borrower)
    {
        return view('borrowers.edit', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        $borrower->update($request->all());

        return redirect()->route('borrowers.index')
            ->with('success', 'Borrower updated successfully');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();

        return redirect()->route('borrowers.index')
            ->with('success', 'Borrower deleted successfully');
    }

    public function show($id)
    {
        $borrower = Borrower::findOrFail($id);
        return view('borrowers.show', compact('borrower'));
    }
}
