<?php

namespace App\Observers;

use App\Models\School;
use Illuminate\Support\Str;

class SchoolsObserver
{
    public function creating(School $school)
    {
        $school->uuid = Str::uuid();
    }
}
