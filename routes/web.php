<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RepaymentController;

use App\Http\Controllers\InsuranceLedgerController;

use App\Http\Controllers\ShareLedgerController;

use App\Http\Controllers\LedgerController;

use App\Http\Controllers\SettingsController;

use App\Http\Controllers\SystemRuleController;

use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
        try {
            DB::connection()->getPdo();

            return response()->json([
                'status' => 'ok',
                'database' => 'connected',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Borrowers
    |--------------------------------------------------------------------------
    */
    Route::resource('borrowers', BorrowerController::class)
        ->middleware('role:admin|secretary');


    /*
    |--------------------------------------------------------------------------
    | Contributions
    |--------------------------------------------------------------------------
    */
    Route::get('/contributions/arrears', [
        ContributionController::class,
        'arrearsDashboard'
    ])->name('contributions.arrears');


    /*
    |--------------------------------------------------------------------------
    | Loans
    |--------------------------------------------------------------------------
    */
    //Route::resource('loans', LoanController::class);
    
    Route::resource('loans', LoanController::class)
	    ->middleware('role:admin|treasurer');


    /*
    |--------------------------------------------------------------------------
    | LOAN WORKFLOW
    |--------------------------------------------------------------------------
    */


    Route::get(

        '/loans/{loan}/schedule',

        [LoanController::class, 'schedule']

        )->name('loans.schedule');

    
    //Route::patch('/loans/{id}/approve', [LoanController::class, 'approve'])
    //->name('loans.approve');
    Route::patch(

         '/loans/{id}/approve',

         [LoanController::class, 'approve']

        )->name('loans.approve');

    
    //Route::patch('/loans/{id}/reject', [LoanController::class, 'reject'])
    //->name('loans.reject');
    Route::patch(

         '/loans/{id}/reject',

         [LoanController::class, 'reject']

        )->name('loans.reject');

    
    //Route::patch('/loans/{id}/disburse', [LoanController::class, 'disburse'])
    //->name('loans.disburse');    
    Route::patch(

        '/loans/{id}/disburse',

        [LoanController::class, 'disburse']

        )->name('loans.disburse');


    /*
    |--------------------------------------------------------------------------
    | REPAYMENTS
    |--------------------------------------------------------------------------
    */

    //Route::resource('repayments', RepaymentController::class);
    Route::resource(

         'repayments',
         RepaymentController::class
    );

    
    /*
    |--------------------------------------------------------------------------
    | RECEIPTS
    |--------------------------------------------------------------------------
    */
    Route::get(

         '/repayments/{repayment}/receipt',

    [RepaymentController::class, 'receipt']

    )->name('repayments.receipt');


    /*
    |--------------------------------------------------------------------------
    | Contributions
    |--------------------------------------------------------------------------
    */
    
    Route::get(
        '/contributions/{contribution}/receipt',
        [ContributionController::class, 'receipt']
    )->name('contributions.receipt');

    Route::get(
        '/contributions/schedules',
        [ContributionController::class, 'schedules']
    )->name('contributions.schedules');

    Route::get(
        '/contributions/arrears',
        [ContributionController::class, 'arrearsDashboard']
    )->name('contributions.arrears');

    Route::get(
        '/contributions/ledger',
        [ContributionController::class, 'ledger']
    )->name('contributions.ledger');

    Route::get(
        '/contributions/export/excel',
        [ContributionController::class, 'exportExcel']
    )->name('contributions.export.excel');

    Route::get(
        '/contributions/export/styled-excel',
        [ContributionController::class, 'exportStyledExcel']
    )->name('contributions.export.styled');

    Route::get(
        '/contributions/export/pdf',
        [ContributionController::class, 'exportPdf']
    )->name('contributions.export.pdf');

    Route::get(
        '/contributions/member/{borrower}/statement',
        [ContributionController::class, 'memberStatement']
    )->name('contributions.member.statement');


    /*
|--------------------------------------------------------------------------
| CONTRIBUTION REPORTS
|--------------------------------------------------------------------------
*/

    Route::get(
        '/contributions/statements/member',
        [ContributionController::class, 'memberStatement']
    )->name('contributions.statement');

    Route::get(
        '/contributions/reports/treasurer',
        [ContributionController::class, 'treasurerReport']
    )->name('contributions.treasurer');

    Route::get(
        '/contributions/reports/audit',
        [ContributionController::class, 'auditExport']
    )->name('contributions.audit');

    //Analytics
    Route::get(
        '/contributions/analytics',
        [ContributionController::class, 'analytics']
    )->name('contributions.analytics');

    Route::resource('contributions', ContributionController::class)
        ->middleware('role:admin|treasurer');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index')
        ->middleware('role:admin|treasurer');


    Route::get(
        '/insurance-ledger',
        [InsuranceLedgerController::class, 'index']
    )->name('insurance.ledger');


    Route::get(
        '/shares-ledger',
        [ShareLedgerController::class, 'index']
    )->name('shares.ledger');


    Route::get('/ledger', [LedgerController::class, 'index'])
    ->name('ledger.index');


    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::post('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');

    
        /*
        |--------------------------------------------------------------------------
        | Contribution Policies
        |--------------------------------------------------------------------------
        */

    Route::resource(
        'rules',
        SystemRuleController::class
    )->except([
        'show',
        'destroy'
    ]);


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';
