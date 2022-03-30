<?php

namespace App\Services\Environment;

use App\Support\Constants;
use PragmaRX\Version\Package\Facade as Version;

class Service
{
    public function data()
    {
        return coollect([
            'app' => [
                'name' => config('app.name'),
                'key' => auth()->user() ? auth()->user()->client_key : config('app.client_key'),
                'id' => auth()->user() ? auth()->user()->email : csrf_token(),
                'version' => '1.0', //Version::format('compact'),
            ],

            'env' => ['default_joined_checkbox' => config('app.default_joined_checkbox')],

            'form' => [
                'mode' => session(Constants::SESSION_FORM_MODE, Constants::FORM_MODE_SHOW),
            ],

            'token' => csrf_token(),

            'captcha_enabled' =>
                app()->environment() == 'local' && env('DISABLE_CAPTCHA') ? false : true,

            'captcha_siteKey' => config('services.recaptcha.siteKey'),

            'debug' => config('app.debug'),

            'session' => [
                'lifetime' => config('session.lifetime'),

                'frontend' => [
                    'lifetime' => config('session.frontend.lifetime'),

                    'warning' => config('session.frontend.warning'),
                ],
            ],

            'user' => auth()->user()
                ? auth()
                    ->user()
                    ->append(['roles', 'abilities'])
                : null,

            'timestamp' => now()->toDateTimeString(),

            'old' => coollect(old())
                ->except('password', 'password_confirmation')
                ->toArray(),

            'events' => [
                'confirmation' => [
                    'enabled' => config('events.confirmation.enabled'),
                ],
            ],

            'broadcast' => [
                'driver' => config('broadcasting.default', 'pusher'),
            ],

            'pusher' => [
                'server' => config('broadcasting.connections.pusher.server'),

                'key' => config('broadcasting.connections.pusher.key'),

                'options' => [
                    'cluster' => config('broadcasting.connections.pusher.options.cluster', 'us2'),
                    'encrypted' => config(
                        'broadcasting.connections.pusher.options.encrypted',
                        false
                    ),
                    'host' => config(
                        'broadcasting.connections.pusher.options.backend_host',
                        '127.0.0.1'
                    ),
                    'port' => config('broadcasting.connections.pusher.options.port', '6001'),
                    'scheme' => config('broadcasting.connections.pusher.options.scheme', 'http'),
                ],
            ],
        ]);
    }
}
