<?php

namespace App\Http\Responses;

use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Spatie\Permission\Models\Permission;

class LoginResponse implements LoginResponseContract
{

    public function toResponse($request)
    {
        // below is the existing response
        // replace this with your own code
        // the user can be located with Auth facade

        $user = Auth::user();

        if ($user->hasPermissionTo('admin'))
        {
            return redirect()->intended(route('users'));
        } else if ($user->hasAnyPermission(['all_schools','school','section'])) {
            return redirect()->route('schools');
        } else if ($user->hasPermissionTo('base')) {
            return redirect()->intended(route('single-student'));
        }

        return redirect()->route('logout');
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
    }

}
