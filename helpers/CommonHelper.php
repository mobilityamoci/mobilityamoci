<?php


use App\Models\Comune;
use App\Models\School;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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


function getComuniArray()
{
    return Comune::all()->pluck(['label'], ['cod_istat']);
}

function getComuneByName($name)
{
    $comuni = Comune::all();
    $residenza_town_istat = array_search(strtoupper($name), $comuni->pluck('label', 'cod_istat')->toArray());

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

function matchTransportNameToId(string $name)
{
    return match (true) {
        Str::contains(Str::lower($name), ['bus', 'bus comunale', 'autobus', 'pullman', 'pulman', 'autobus comunale']) => Transport::BUS_COMUNALE,
        Str::contains(Str::lower($name), ['macchina', 'auto', 'automobile', 'moto', 'motocicletta', 'motorino', 'scooter']) => Transport::AUTO,
        Str::contains(Str::lower($name),'auto collettiva (2 studenti)') => Transport::AUTO_2,
        Str::contains(Str::lower($name),'auto collettiva (3+ studenti)') => Transport::AUTO_3,
        Str::contains(Str::lower($name),['piedi', 'a piedi']) => Transport::PIEDI,
        Str::contains(Str::lower($name),['bici', 'bicicletta']) => Transport::BICICLETTA,
        Str::contains(Str::lower($name),'treno') => Transport::TRENO,
        default => Transport::AUTO //OPINABILE
    };

}

function getUserSchools($with = [], $withCount = []): Collection
{
    $user = Auth::user();
    if ($user->can('all_schools')) {
        return  School::with($with)->withCount($withCount)->get();
    } else {
        return $user->schools()->with($with)->withCount($withCount)->get();
    }
}
