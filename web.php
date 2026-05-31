<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\InsuranceLedgerController;

use App\Http\Controllers\SettingsController;

use App\Http\Controllers\SystemRuleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('borrowers', BorrowerController::class);

    Route::resource('loans', LoanController::class);

    Route::resource('contributions', ContributionController::class);

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get(
        '/insurance-ledger',
        [InsuranceLedgerController::class, 'index']
    )->name('insurance.ledger');


    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');


    Route::resource(
        'rules',
        SystemRuleController::class
        )->except(['show','destroy']);

        
    Route::middleware('verified')->group(function () {

        // future verified-only routes

    });

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
