<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Borrower;
use Illuminate\Http\Request;

use App\Services\ContributionEngineService;
use App\Models\ContributionSchedule;
use Carbon\Carbon;

use App\Exports\ContributionsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContributionsStyledExport;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\InsuranceLedger;

use App\Services\SettingService;

class ContributionController extends Controller
{
    public function index(
        Request $request,
        ContributionEngineService $engine
        )
    {
        $search = $request->search;
        $currentMonth = now()->format('Y-m');

        $engine->generateMonthlySchedules();
        $engine->processArrears();

        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTIONS TABLE
        |--------------------------------------------------------------------------
        */

        $contributions = Contribution::with('borrower')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('borrower', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | KPI CARDS
        |--------------------------------------------------------------------------
        */

        $monthlyContributions = Contribution::where('period', $currentMonth);

        $monthlyTotal = $monthlyContributions->sum('amount');

        $paidMemberIds = $monthlyContributions
            ->pluck('borrower_id')
            ->unique();

        $totalMembers = Borrower::count();

        $paidCount = $paidMemberIds->count();

        $pendingCount = $totalMembers - $paidCount;

        $totalContributions = Contribution::sum('amount');


        /*
        |--------------------------------------------------------------------------
        | TREASURER ANALYTICS
        |--------------------------------------------------------------------------
        */

        $expectedCollections = $totalMembers * 1300;

        $actualCollections = $monthlyTotal;

        $collectionRate = $expectedCollections > 0
            ? ($actualCollections / $expectedCollections) * 100
            : 0;

        $totalArrearsAmount = ContributionSchedule::sum('balance');

        $topContributors = Contribution::with('borrower')
            ->selectRaw('borrower_id, SUM(amount) as total')
            ->groupBy('borrower_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        
        /*
        |--------------------------------------------------------------------------
        | MONTHLY COLLECTION CHART
        |--------------------------------------------------------------------------
        */

        $monthlyTrend = Contribution::selectRaw("
            period,
            SUM(amount) as total
        ")
            ->groupBy('period')
            ->orderBy('period')
            ->take(12)
            ->get();

        $chartLabels = $monthlyTrend
            ->pluck('period');

        $chartData = $monthlyTrend
            ->pluck('total');


        /*
        |--------------------------------------------------------------------------
        | MEMBER SPLIT PANELS
        |--------------------------------------------------------------------------
        */

        $paidMembers = Borrower::whereIn('id', $paidMemberIds)
            ->orderBy('firstname')
            ->get();

        $pendingMembers = Borrower::whereNotIn('id', $paidMemberIds)
            ->orderBy('firstname')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FORM DROPDOWNS
        |--------------------------------------------------------------------------
        */

        $borrowers = Borrower::orderBy('firstname')->get();

        /*
        |--------------------------------------------------------------------------
        | MONTHLY COLLECTION ANALYTICS
        |--------------------------------------------------------------------------
        */

        $monthlyAnalytics = Contribution::selectRaw(
            'period, SUM(amount) as total'
        )
        ->groupBy('period')
        ->orderBy('period')
        ->get();

        return view('contributions.index', compact(
            'contributions',
            'borrowers',
            'monthlyTotal',
            'totalContributions',
            'paidCount',
            'pendingCount',
            'paidMembers',
            'pendingMembers',
            'expectedCollections',
            'actualCollections',
            'collectionRate',
            'totalArrearsAmount',
            'topContributors',
            'chartLabels',
            'chartData',
            'monthlyAnalytics',
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        ContributionEngineService $engine
        )
    {
        $data = $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'amount' => 'required|numeric|min:1',
            'contribution_date' => 'required|date',
            'type' => 'required',
            'notes' => 'nullable',
        ]);

        $borrower = Borrower::findOrFail($data['borrower_id']);

        $data['period'] = date(
            'Y-m',
            strtotime($data['contribution_date'])
        );

        $exists = Contribution::where('borrower_id', $data['borrower_id'])
            ->where('period', $data['period'])
            ->where('type', $data['type'])
            ->exists();

        if ($exists) {

            return back()->withErrors([
                'borrower_id' =>
                    'Contribution already recorded for this period.'
            ]);
        }

        $data['status'] = 'paid';

        //$data['chama_id'] = $borrower->chama_id;

        //Contribution::create($data);

        $data['chama_id'] = $borrower->chama_id;

        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTION POLICY ENGINE
        |--------------------------------------------------------------------------
        |
        | 1300
        | = 1000 shares
        | = 300 insurance
        |
        */

        if ($data['amount'] >= 1300) {

        $data['shares_amount'] = 1000;

        $data['insurance_amount'] = 300;

        } else {

        /*
        |--------------------------------------------------------------------------
        | PARTIAL PAYMENT SPLIT
        |--------------------------------------------------------------------------
        */

        $sharesRatio = 1000 / 1300;

        $insuranceRatio = 300 / 1300;

        $data['shares_amount'] =
            round($data['amount'] * $sharesRatio, 2);

        $data['insurance_amount'] =
            round($data['amount'] * $insuranceRatio, 2);
        }

        //Contribution::create($data);

        $contribution = Contribution::create([

            ...$data,

            'receipt_no' => 'RCPT-' . now()->format('Ymd') . '-' . rand(1000,9999)

        ]);


        /*
        |--------------------------------------------------------------------------
        | INSURANCE LEDGER ENTRY
        |--------------------------------------------------------------------------
        */

        InsuranceLedger::create([

            'borrower_id' => $borrower->id,

            'contribution_id' => $contribution->id,

            'amount' => $data['insurance_amount'],

            'transaction_type' => 'credit',

            'description' => 'Insurance contribution allocation',

            'transaction_date' => now(),

        ]);

        $engine->applyContribution(
            //$contribution,
            $borrower->id,
            $data['amount'],
            $data['period']
        );

        $engine->postSharesLedger($contribution);

        $engine->postInsuranceLedger($contribution);

        return back()->with(
            'success',
            'Contribution recorded successfully'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Contribution $contribution)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'contribution_date' => 'required|date',
            'status' => 'required',
            'type' => 'required',
            'notes' => 'nullable',
        ]);

        $data['period'] = date(
            'Y-m',
            strtotime($data['contribution_date'])
        );

        $contribution->update($data);

        return back()->with(
            'success',
            'Contribution updated successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RECEIPT
    |--------------------------------------------------------------------------
    */

    public function receipt(Contribution $contribution)
    {
        return view(
            'contributions.receipt',
            compact('contribution')
        );
    }

    
    /*
    |--------------------------------------------------------------------------
    | CONTRIBUTION SCHEDULE DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function schedules()
    {
        $schedules = ContributionSchedule::with('borrower')
            ->latest()
            ->paginate(15);

    /*
    |--------------------------------------------------------------------------
    | KPI
    |--------------------------------------------------------------------------
    */

    $totalExpected = ContributionSchedule::sum('expected_amount');

    $totalPaid = ContributionSchedule::sum('paid_amount');

    $totalBalance = ContributionSchedule::sum('balance');

    $totalPenalties = ContributionSchedule::sum('penalty');

    return view(
        'contributions.schedules',
        compact(
            'schedules',
            'totalExpected',
            'totalPaid',
            'totalBalance',
            'totalPenalties'
        )
      );
    }

    public function arrearsDashboard()
    {
        $today = Carbon::today();

        $arrears = ContributionSchedule::with('borrower')
            ->where('status', 'overdue')
            ->orWhere(function ($q) use ($today) {
                $q->where('due_date', '<', $today)
                ->where('status', '!=', 'paid');
            })
            ->get();

        $totalOverdueMembers = $arrears
            ->pluck('borrower_id')
            ->unique()
            ->count();

        $totalArrears = $arrears->sum('balance');

        $totalPenalties = $arrears->sum('penalty');

        $partialPayments = $arrears
            ->where('status', 'partial')
            ->count();

        return view('contributions.arrears', compact(
            'arrears',
            'totalOverdueMembers',
            'totalArrears',
            'totalPenalties',
            'partialPayments'
        ));
    }


    public function ledger(Request $request)
    {
        $query = Contribution::with('borrower');

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        if ($request->member) {

            $query->where('borrower_id', $request->member);
        }

        if ($request->status) {

            $query->where('status', $request->status);
        }

        if ($request->type) {

            $query->where('type', $request->type);
        }

        if ($request->month) {

            $query->where('period', $request->month);
        }

        if ($request->from && $request->to) {

            $query->whereBetween(
                'contribution_date',
                [$request->from, $request->to]
            );
        }

        $contributions = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $borrowers = Borrower::orderBy('firstname')->get();

        return view(
            'contributions.ledger',
            compact('contributions', 'borrowers')
        );
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(

            new ContributionsExport([

                'borrower_id' => $request->borrower_id,

                'status' => $request->status,

                'type' => $request->type,

                'month' => $request->month,

                'from' => $request->from,

                'to' => $request->to,

            ]),

            'contributions-report.xlsx'
        );
    }

    public function exportStyledExcel(Request $request)
    {
        $contributions = $this->filteredContributions($request)
            ->latest()
            ->get();

        return Excel::download(
            new ContributionsStyledExport($contributions),
            'styled-contributions.xlsx'
        );
    }


    public function exportPdf(Request $request)
    {
    $contributions = $this->filteredContributions($request)
        ->latest()
        ->get();

    $totalAmount = $contributions->sum('amount');

    $pdf = Pdf::loadView(
        'contributions.exports.pdf',
        compact(
            'contributions',
            'totalAmount'
        )
    );

    return $pdf->download(
        'contributions-report.pdf'
    );
    }


    private function filteredContributions($request)
    {
        return Contribution::with('borrower')

            ->when($request->member, function ($q) use ($request) {
                $q->whereHas('borrower', function ($b) use ($request) {
                    $b->whereRaw(
                        "firstname || ' ' || lastname LIKE ?",
                        ["%{$request->member}%"]
                    );
                });
            })

            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->when($request->type, function ($q) use ($request) {
                $q->where('type', $request->type);
            })

            ->when($request->month, function ($q) use ($request) {
                $q->where('period', $request->month);
            })

            ->when($request->from_date, function ($q) use ($request) {
                $q->whereDate(
                    'contribution_date',
                    '>=',
                    $request->from_date
                );
            })

            ->when($request->to_date, function ($q) use ($request) {
                $q->whereDate(
                    'contribution_date',
                    '<=',
                    $request->to_date
                );
            });
    }


    /*
    |--------------------------------------------------------------------------
    | MEMBER STATEMENT
    |--------------------------------------------------------------------------
    */

    public function memberStatement(Borrower $borrower)
    {
    $contributions = Contribution::where(
            'borrower_id',
            $borrower->id
        )
        ->latest()
        ->get();

    $totalAmount = $contributions->sum('amount');

    $pdf = Pdf::loadView(
        'contributions.exports.member-statement',
        compact(
            'borrower',
            'contributions',
            'totalAmount'
        )
    );

    return $pdf->stream(
        $borrower->full_name . '-statement.pdf'
    );
    }


    /*
|--------------------------------------------------------------------------
| MEMBER STATEMENT
|--------------------------------------------------------------------------
*/

    public function memberStatements(Request $request)
    {
    $query = Contribution::with('borrower');

    if ($request->borrower_id) {

        $query->where(
            'borrower_id',
            $request->borrower_id
        );
    }

    if ($request->from && $request->to) {

        $query->whereBetween(
            'contribution_date',
            [
                $request->from,
                $request->to
            ]
        );
    }

    $contributions = $query
        ->orderBy('contribution_date')
        ->get();

    $total = $contributions->sum('amount');

    return view(
        'contributions.reports.statement',
        compact(
            'contributions',
            'total'
        )
    );
    }


    /*
|--------------------------------------------------------------------------
| TREASURER REPORT
|--------------------------------------------------------------------------
*/

    public function treasurerReport(Request $request)
    {
    $query = Contribution::with('borrower');

    if ($request->month) {

        $query->where(
            'period',
            $request->month
        );
    }

    $contributions = $query
        ->latest()
        ->get();

    $summary = [

        'total_amount' =>
            $contributions->sum('amount'),

        'paid_members' =>
            $contributions
                ->pluck('borrower_id')
                ->unique()
                ->count(),

        'transactions' =>
            $contributions->count(),
    ];

    return view(
        'contributions.reports.treasurer',
        compact(
            'contributions',
            'summary'
        )
    );
    }


    /*
    |--------------------------------------------------------------------------
    | AUDIT EXPORT
    |--------------------------------------------------------------------------
    */

    public function auditExport()
    {
        $contributions = Contribution::with('borrower')
            ->latest()
            ->get();

        return view(
            'contributions.reports.audit',
            compact('contributions')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ANALYTICS DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function analytics()
    {
    /*
    |--------------------------------------------------------------------------
    | MONTHLY COLLECTIONS
    |--------------------------------------------------------------------------
    */

    $monthlyData = Contribution::selectRaw(
            'period, SUM(amount) as total'
        )
        ->groupBy('period')
        ->orderBy('period')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | STATUS BREAKDOWN
    |--------------------------------------------------------------------------
    */

    $paidCount = Contribution::where(
        'status',
        'paid'
    )->count();

    $partialCount = Contribution::where(
        'status',
        'partial'
    )->count();

    $pendingCount = Contribution::where(
        'status',
        'pending'
    )->count();

    /*
    |--------------------------------------------------------------------------
    | TYPE BREAKDOWN
    |--------------------------------------------------------------------------
    */

    $monthlyType = Contribution::where(
        'type',
        'monthly'
    )->count();

    $registrationType = Contribution::where(
        'type',
        'registration'
    )->count();

    return view(
        'contributions.analytics',
        compact(
            'monthlyData',
            'paidCount',
            'partialCount',
            'pendingCount',
            'monthlyType',
            'registrationType'
        )
    );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(Contribution $contribution)
    {
        $contribution->delete();

        return back()->with(
            'success',
            'Contribution deleted successfully'
        );
    }

}