<?php

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

Route::get('login', Login::class)->name('login');

Route::get('/', Home::class)->name('/');

Route::get('perkembangan-ananda', Perkembangan::class)->name('perkembangan-ananda');
