<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;

class LizmapService
{
    public function __construct()
    {

    }

    public function generateLizmapLink(User $user, iterable|null $schools = null)
    {
        $host = config('custom.lizmap.host');

        if ($schools)
            $schools = School::whereIn('id', $schools)->get();

        if ($user->hasAnyRole(['Admin', 'MMProvinciale'])) {
            if (!$schools)
                $schools = School::all();

            $projectName = config('custom.lizmap.provinciale');
        } else if ($user->hasAnyRole(['MMScolastico', 'Insegnante'])) {
            if (!$schools)
                $schools = $user->schools;
            $projectName = config('custom.lizmap.scolastico');
        } else {
            if (!$schools)
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
