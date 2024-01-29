<?php

use App\Http\Livewire\AcceptUsers;
use App\Http\Livewire\Schools;
use App\Http\Livewire\ShowMappa;
use App\Http\Livewire\SingleStudent;
use App\Http\Livewire\Students;
use App\Http\Livewire\Users;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('profile.show');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//Route::get('redirecting', function (StatefulGuard $guard) {
//    dd('yo');
//    if (is_null(Auth::user()->accepted_at)) {
//        $guard->logout();
//        request()->session()->invalidate();
//        request()->session()->regenerateToken();
//        return redirect()->route('login');
//    }
//    return redirect(Auth::user()->homeRoute());
//})->name('redirecting');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', function () {
        if (Auth::check())
            return redirect(Auth::user()->homeRoute());
        else
            return redirect()->route('login');
    });
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');

    Route::middleware(['role_or_permission:all_schools|school|section'])->get('/studenti', Students::class)->name('students');
    Route::middleware(['can:admin'])->get('/scuole', Schools::class)->name('schools');
    Route::middleware(['can:admin'])->get('/utenti', Users::class)->name('users');
    Route::middleware(['can:base'])->get('/informazioni', SingleStudent::class)->name('single-student');
    Route::middleware(['role_or_permission:all_schools|school|section'])->get('/mappa', ShowMappa::class)->name('mappa.index');
    Route::get('/accetta-utenti', AcceptUsers::class)->name('accept.users');


});
