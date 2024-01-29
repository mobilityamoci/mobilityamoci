<?php

namespace App\Services;

use App\Models\User;

class LizmapService
{
    public function __construct()
    {

    }

    public function generateLizmapFilter(User $user)
    {
        if ($user->hasAnyRole(['Admin', 'MMProvinciale']))
            return null;
        else if ($user->hasAnyRole(['MMScolastico', 'Insegnante'])) {

            $schools = $user->schools;
            $str = '&my_filter=';
            foreach ($schools as $school) {
                $str .= '' . $school->uuid . '';
                if (next($schools)) $str .= ",";
            }

            return $str;

        }
        return null;

    }
}
