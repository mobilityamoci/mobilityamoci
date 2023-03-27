<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Http\Responses\VerifyEmailResponse;
use App\Models\School;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );

        $this->app->singleton(
            \Laravel\Fortify\Contracts\RegisterResponse::class,
            \App\Http\Responses\RegisterResponse::class,
        );

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (is_null(optional($user)->accepted_at)) {
                throw ValidationException::withMessages([
                    Fortify::username() => "Non sei ancora stato accettato. Verifica con il responsabile della tua scuola.",
                ]);
            }

            if ($user &&
                !is_null($user->accepted_at) &&
                Hash::check($request->password, $user->password)) {
                return $user;
            }



            return false;
        });

        Fortify::registerView(function () {
            $schools = School::all();
            $sections = Section::all();
            return view('auth.register', compact('schools','sections'));
        });
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
