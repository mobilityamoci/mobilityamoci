<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {
        // below is the existing response
        // replace this with your own code
        // the user can be located with Auth facade

        $user = Auth::user();

        if (is_null($user->accepted_at)) {
            return redirect()->route('logout');
        }


        if ($user->hasAnyRole(['MMProvinciale','MMScolastico','Insegnante']))
        {
            return redirect()->intended(route('students'));
        } else if ($user->hasAnyRole(['Utente Base'])) {
            return redirect()->intended(route('single-student.info'));
        } else if ($user->hasRole('Admin')) {
            return redirect()->intended(route('users'));
        }

        return redirect()->route('logout');
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
    }

}
