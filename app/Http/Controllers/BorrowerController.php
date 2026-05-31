<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
	public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | SEARCH QUERY
        |--------------------------------------------------------------------------
        */

        $query = Borrower::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('firstname', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('contact_no', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | MEMBERS
        |--------------------------------------------------------------------------
        */

        $borrowers = $query
            ->latest()
            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | KPI ENGINE
        |--------------------------------------------------------------------------
        */

        $totalMembers = Borrower::count();

        $activeBorrowers = Borrower::whereHas('loans', function ($q) {

            $q->whereIn('loan_status', [

                'active',
                'ongoing',
                'overdue',

            ]);

        })->count();

        $totalContributions =
            \App\Models\Contribution::sum('amount');

        $totalLoanPortfolio =
            \App\Models\Loan::sum('balance');

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('borrowers.index', compact(

            'borrowers',

            'totalMembers',
            'activeBorrowers',

            'totalContributions',
            'totalLoanPortfolio',
        ));
    }

    public function create()
    {
        return view('borrowers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'firstname' => 'required|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'lastname' => 'required|string|max:255',
        'contact_no' => 'required|string|max:20',
        'email' => 'nullable|email',
        'tax_id' => 'nullable|string|max:100',
        'address' => 'nullable|string',
        ]);

    Borrower::create([
        'firstname' => $request->firstname,
        'middlename' => $request->middlename,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'contact_no' => $request->contact_no,
        'address' => $request->address,
        'tax_id' => $request->tax_id,
        'chama_id' => 1,
    ]);

    return redirect()
        ->route('borrowers.index')
        ->with('success', 'Member created successfully');
    }

    public function edit(Borrower $borrower)
    {
        return view('borrowers.edit', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
    $request->validate([
        'firstname' => 'required|string|max:255',
        'middlename' => 'nullable|string|max:255',
        'lastname' => 'required|string|max:255',
        'contact_no' => 'required|string|max:20',
        'email' => 'nullable|email',
        'tax_id' => 'nullable|string|max:100',
        'address' => 'nullable|string',
    ]);

    $borrower->update([
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'contact_no' => $request->contact_no,
        'address' => $request->address,
    ]);

    return redirect()
        ->route('borrowers.index')
        ->with('success', 'Member updated successfully');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();

        return redirect()->route('borrowers.index')
            ->with('success', 'Borrower deleted successfully');
    }

    public function show($id)
    {
        $borrower = Borrower::with([

            'loans.repayments',
            'contributions',

        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | MEMBER KPIs
        |--------------------------------------------------------------------------
        */

        $totalContributions =
            $borrower->contributions->sum('amount');

        $totalLoans =
            $borrower->loans->sum('amount');

        $totalOutstanding =
            $borrower->loans->sum('balance');

        $totalRepaid =
            $borrower->loans->sum(function ($loan) {

                return $loan->repayments->sum('amount');

        });

        $activeLoans =
            $borrower->loans->whereIn('loan_status', [

                'active',
                'ongoing',
                'overdue',

            ])->count();

        $completedLoans =
            $borrower->loans
                ->where('loan_status', 'completed')
                ->count();

        $overdueLoans =
            $borrower->loans
                ->where('loan_status', 'overdue')
                ->count();

        /*
        |--------------------------------------------------------------------------
        | CREDIT ENGINE
        |--------------------------------------------------------------------------
        */

        $creditScore =
            $borrower->calculateCreditScore();

        $riskGrade =
            $borrower->riskGrade();

        /*
        |--------------------------------------------------------------------------
        | DATASETS
        |--------------------------------------------------------------------------
        */

        $loans = $borrower->loans->sortByDesc('created_at');

        $contributions = $borrower->contributions
            ->sortByDesc('created_at');

        $repayments = $borrower->loans
            ->flatMap(function ($loan) {

                return $loan->repayments;

            })
            ->sortByDesc('created_at');

        return view('borrowers.show', compact(

            'borrower',

            'totalContributions',
            'totalLoans',
            'totalOutstanding',
            'totalRepaid',

            'activeLoans',
            'completedLoans',
            'overdueLoans',

            'creditScore',
            'riskGrade',

            'loans',
            'contributions',
            'repayments',

        ));
    }

}
