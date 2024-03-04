<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;

class LizmapService
{
    public function __construct()
    {

    }

    public function generateLizmapLink(User $user)
    {
        $host = config('custom.lizmap.host');
        if ($user->hasAnyRole(['Admin', 'MMProvinciale'])) {
            $schools = School::all();
            $projectName = config('custom.lizmap.provinciale');
        } else if ($user->hasAnyRole(['MMScolastico', 'Insegnante'])) {
            $schools = $user->schools;
            $projectName = config('custom.lizmap.scolastico');
        } else {
            $schools = null;
            $projectName = null;
        }

        $filter = '&my_filter=';
        foreach ($schools as $school) {
            $filter .= $school->uuid;
            $filter .= ",";
        }

        return $host . '/view/embed?repository=' . $projectName . '&project=' . $projectName . $filter;


    }
}
