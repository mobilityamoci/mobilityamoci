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
        $prefix = '&filter=Scuola:"scuola_id" IN ';
        if ($user->hasAnyRole(['Admin', 'MMProvinciale']))
            return null;
        else if ($user->hasAnyRole(['MMScolastico', 'Insegnante'])) {

            $schools = $user->schools;

            $str = '(';
            foreach ($schools as $school) {
                $str .= '"' . $school->uuid . '"';
                if (next($schools)) $str .= ",";
            }
            $str .= ')';

            $prefix .= $str;
            return $prefix;

        }
        return null;

        return '&filter=Scuola:"scuola_id" IN ( 3, )';

    }
}
