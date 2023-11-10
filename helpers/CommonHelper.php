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
    $comuni = getAllComuni();
    if ($comuni->has($town_istat)) {
        return Cache::get('comuni')[$town_istat]['comune'];
    }
    return null;
}


function getAllComuni()
{
    return Cache::remember('comuni', 60 * 24, function () {
        $response = Http::withToken(config('openapi.towns_token'))
            ->retry(3, 100)
            ->throw()
            ->get('https://cap.openapi.it/cerca_comuni?provincia=piacenza');

        $arr = $response->json()['data']['result'];

        $response = Http::withToken('63cff56cabd5b551b243e868')
            ->retry(3, 100)
            ->throw()
            ->get('https://cap.openapi.it/cerca_comuni?provincia=parma');

        $arr = array_merge($arr, $response->json()['data']['result']);

        $response = Http::withToken(config('openapi.towns_token'))
            ->retry(3, 100)
            ->throw()
            ->get('https://cap.openapi.it/cerca_comuni?provincia=cremona');

        $arr = array_merge($arr, $response->json()['data']['result']);

        return collect($arr)->map(function ($item) {
            $item['soundex'] = soundex($item['comune']);
            return $item;
        })->keyBy('istat');
    });
}

function getComuneByName($name)
{
    $comuni = getAllComuni();
    $residenza_town_istat = array_search($name, $comuni->pluck('comune', 'istat')->toArray());

    if ($residenza_town_istat)
        return $residenza_town_istat;

    $residenza_town_istat = array_search(soundex($name), $comuni->pluck('soundex', 'istat')->toArray());

    if ($residenza_town_istat)
        return $residenza_town_istat;

    return config('custom.geo.piacenza_istat');

}

function sanitizeAddress(string $string)
{
    $string = preg_replace('/\w*\.\w*/i', '', $string);
    return preg_replace('/w{1,2}$/i', '', $string);
}
