<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\LoanController;
use App\Livewire\Customers\Index as CustomersIndex;
use App\Livewire\Dashboard;
use App\Livewire\Loans\Index as LoansIndex;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|-------------------------------------------------------------------------
| Auth (Breeze-like, implemented with Livewire)
|-------------------------------------------------------------------------
| You asked for Laravel Breeze + Livewire login.
| This project is already Livewire v3, so we ship a simple Livewire auth.
| If you later want official Breeze scaffolding, you can install it on your
| machine with composer; routes below will still make sense.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function () {
    auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

/*
|-------------------------------------------------------------------------
| App pages (protected)
|-------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Route::resource('customers', CustomerController::class);
    // Route::resource('loans', LoanController::class);
    Route::get('/customers', CustomersIndex::class)->name('customers.index');
    Route::get('/loans', LoansIndex::class)->name('loans.index');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});
