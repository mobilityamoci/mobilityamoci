<?php

namespace App\Providers;

use App\Interfaces\IQgisService;
use App\Models\Student;
use App\Observers\StudentsObserver;
use App\Services\QgisService;
use Illuminate\Support\ServiceProvider;

class GeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IQgisService::class, QgisService::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
