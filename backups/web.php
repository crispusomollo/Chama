<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

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
| Admin + Secretary
|--------------------------------------------------------------------------
*/

Route::middleware(['role:admin|secretary'])->group(function () {

    Route::resource('borrowers', BorrowerController::class);

});


/*
|--------------------------------------------------------------------------
| Admin + Treasurer
|--------------------------------------------------------------------------
*/

    Route::middleware(['role:admin|treasurer'])->group(function () {

    Route::resource('contributions', ContributionController::class);

    Route::resource('loans', LoanController::class);

    Route::get('/reports', [ReportsController::class, 'index'])
        ->name('reports');

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

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
