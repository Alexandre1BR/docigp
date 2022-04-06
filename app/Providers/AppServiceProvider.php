<?php

namespace App\Providers;

use App\Extensions\CustomSessionHandler;
use Illuminate\Contracts\Container\Container;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AppServiceProvider extends ServiceProvider
{
    /**
     *
     */
    private function configureBouncer()
    {
        Bouncer::tables([
            'permissions' => 'bouncer_permissions',
            'assigned_roles' => 'bouncer_assigned_roles',
            'roles' => 'bouncer_roles',
            'abilities' => 'bouncer_abilities',
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configureBouncer();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Session::extend('custom', function ($app) {
            $container=$app->make(Container::class);
            return new CustomSessionHandler($container->make('files'),config('session.files'), config('session.lifetime'));
        });

        if (App::environment('local') && config('app.debug')) {
            info(request()->all());
        }

        Paginator::useBootstrap();
    }
}
