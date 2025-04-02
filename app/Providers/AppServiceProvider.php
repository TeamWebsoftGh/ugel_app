<?php

namespace App\Providers;

use App\Events\ActivityTriggered;
use App\Events\NewMaintenanceRequestEvent;
use App\Listeners\LogActivityListener;
use App\Listeners\NewMaintenanceRequestListener;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        if(env('APP_ENV')  == 'production') {
            \URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); // This sets the default string length

        //
        Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10 && strlen($value)<=20;
        });

        Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, 'This field has an invalid phone number');
        });

        Validator::extend('extension', function ($attribute, $value, $parameters, $validator) {
            $extension = $value->getClientOriginalExtension();

            return !empty($extension) && in_array($extension, $parameters);
        },
            trans('validation.custom.invalid_extension')
        );

        Gate::before(function ($user, $ability) {
            return $user->hasRole('developer') ? true : null;
        });

        Event::listen(
            ActivityTriggered::class,
            LogActivityListener::class,
        );

        Event::listen(
            NewMaintenanceRequestEvent::class,
            NewMaintenanceRequestListener::class,
        );
//
//        Event::listen(
//            ActivityTriggered::class,
//            LogActivityListener::class,
//        );

        Request::macro('isApi', function () {
            return $this->is(config('api.prefix') . '/*');
        });
    }
}
