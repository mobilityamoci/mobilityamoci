<?php


use App\Models\School;
use App\Models\User;
use Illuminate\Support\Collection;

function getSchoolsFromUser(User $user): Collection
{
    if ($user->hasPermissionTo('all_schools')) {
        return School::all()->pluck('id');
    } else {
        return $user->schools()->pluck('id');
    }
}
