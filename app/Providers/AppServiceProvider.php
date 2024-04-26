<?php

namespace App\Providers;

use App\Interfaces\IQgisService;
use App\Models\Survey;
use Illuminate\Support\ServiceProvider;
use MattDaneshvar\Survey\Contracts\Survey as SurveyContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SurveyContract::class, Survey::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
