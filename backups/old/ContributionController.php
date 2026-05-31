<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Borrower;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contributions = Contribution::with('borrower')
            ->latest()
            ->get();

        $borrowers = Borrower::all();

        return view('contributions.index', compact('contributions', 'borrowers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrower_id' => 'required',
            'amount' => 'required|numeric',
            'contribution_date' => 'required|date',
        ]);

        Contribution::create($request->all());

        return back()->with('success', 'Contribution added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
