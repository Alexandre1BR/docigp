<?php

namespace App\Http\Middleware;

use App\Events\SessionExpired;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        if($request->method() == 'GET' && ($token = $this->getTokenFromRequest($request)) && !(is_string($sessionToken = $request->session()->token()) &&
                is_string($token) &&
                hash_equals($sessionToken, $token))){
            event(new SessionExpired($token));
        }

        try {
            return parent::handle($request, $next);
        }catch (TokenMismatchException $exception){
            event(new SessionExpired($this->getTokenFromRequest($request)));
            throw $exception;
        }
    }
}
