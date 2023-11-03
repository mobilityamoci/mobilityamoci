<?php


use App\Models\School;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

function getSchoolsFromUser(User $user): Collection
{
    if ($user->hasPermissionTo('all_schools')) {
        return School::all()->pluck('id');
    } else {
        return $user->schools()->pluck('id');
    }
}

function getComune($town_istat)
{

    //TODO: remember non get
    $comuni = Cache::get('comuni');
    if ($comuni->has($town_istat)) {
        return Cache::get('comuni')[$town_istat]['comune'];
    }
    return null;
}
