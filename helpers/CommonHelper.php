<?php


use App\Models\Comune;
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
    return optional(Comune::where('cod_istat', $town_istat)->first())->label ?? '';
}


function getAllComuni()
{
    return Comune::all();
}

function getComuneByName($name)
{
    $comuni = getAllComuni();
    $residenza_town_istat = array_search($name, $comuni->pluck('label', 'cod_istat')->toArray());

    if ($residenza_town_istat)
        return $residenza_town_istat;

    $residenza_town_istat = array_search(soundex($name), $comuni->pluck('soundex', 'cod_istat')->toArray());

    if ($residenza_town_istat)
        return $residenza_town_istat;

    return config('custom.geo.piacenza_istat');

}

function sanitizeAddress(string $string)
{
    $string = preg_replace('/\w*\.\w*/i', '', $string);
    return preg_replace('/\b(?!(via)\b) [a-zA-Z]{1,3} \b/i', ' ', $string);
}
