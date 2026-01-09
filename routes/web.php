<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\LoanController;
use App\Livewire\Customers\Index as CustomersIndex;
use App\Livewire\Dashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('customers', CustomerController::class);
// Route::resource('loans', LoanController::class);
Route::get('/customers', CustomersIndex::class)->name('customers.index');

Route::get('/dashboard', Dashboard::class)->name('dashboard');
