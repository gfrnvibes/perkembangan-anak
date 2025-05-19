<?php

use App\Http\Controllers\LogoutController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Home;
use App\Livewire\Perkembangan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function(){
    Route::get('login', Login::class)->name('login');
});


Route::get('/', Home::class)->name('/');

Route::middleware('auth')->group(function(){
    Route::get('jejak-ananda', Perkembangan::class)->name('perkembangan-ananda');
});

Route::get('logout', [LogoutController::class, 'logout'])->name('logout');

// ADMIN
Route::middleware('admin')->group(function() {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
});