<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Services\Environment\Service as Environment;

class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function compose(View $view)
    {
        $view->with('environment', $this->getEnvironmentService());

        $view->with('app_name', config('app.name'));
    }

    /**
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function getEnvironmentService()
    {
        if (app()->has(Environment::class)) {
            return app()->make(Environment::class);
        }

        $environment = app(Environment::class)->data();

        app()->singleton(Environment::class, function ($app) use ($environment) {
            return $environment;
        });

        return $environment;
    }
}
