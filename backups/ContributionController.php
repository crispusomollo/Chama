<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Borrower;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function index()
    {
        $contributions = Contribution::with('borrower')
            ->latest()
            ->get();

	$borrowers = Borrower::all();

	$monthlyTotal = Contribution::where('period', date('Y-m'))
    ->sum('amount');

$paidCount = Contribution::where('status', 'paid')
    ->count();

        return view('contributions.index', compact('contributions', 'borrowers'));
    }

    public function store(Request $request)
    {
    $data = $request->validate([
        'borrower_id' => 'required',
        'amount' => 'required',
        'contribution_date' => 'required|date',
    ]);

    $borrower = Borrower::findOrFail($data['borrower_id']);

    if (!$borrower->chama_id) {
        return back()->withErrors([
            'borrower_id' => 'Borrower is not assigned to a chama.'
        ]);
    }

    $data['period'] = date(
        'Y-m',
        strtotime($data['contribution_date'])
    );

    //    $data['chama_id'] = 1;
    $data['chama_id'] = $borrower->chama_id;

    Contribution::create($data);

    return back()->with(
        'success',
        'Contribution recorded'
    );
    }

    public function update(Request $request, Contribution $contribution)
    {
    $data = $request->validate([
        'amount' => 'required',
        'contribution_date' => 'required|date',
        'status' => 'required',
        'notes' => 'nullable',
    ]);

    $data['period'] = date('Y-m', strtotime($data['contribution_date']));

    $contribution->update($data);

    return back()->with('success', 'Contribution updated');
    }

    public function destroy(Contribution $contribution)
    {
    $contribution->delete();

    return back()->with('success', 'Contribution deleted');
    }
}
