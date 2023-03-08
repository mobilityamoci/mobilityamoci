<?php

use App\Http\Livewire\Schools;
use App\Http\Livewire\Students;
use App\Http\Livewire\Users;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
Route::get('/', function () {
    return redirect()->route('students');
});
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');

    Route::middleware(['role_or_permission:all_schools|school|section'])->get('/studenti', Students::class)->name('students');
    Route::middleware(['can:admin'])->get('/scuole', Schools::class)->name('schools');
    Route::get('/utenti', Users::class)->name('users');
});
