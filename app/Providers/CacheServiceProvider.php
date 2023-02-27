<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $comuni = Cache::remember('comuni', 60 * 24, function () {
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

            $arr = collect($arr)->keyBy('istat');

            return $arr;
        });

    }
}
