<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\Authentication\Service as AuthenticationService;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var \App\Services\Authentication\Service
     */
    private $authentication;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\Authentication\Service $authentication
     */
    public function __construct(AuthenticationService $authentication)
    {
        $this->middleware('guest')->except('logout');

        $this->authentication = $authentication;
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function loginAlerj(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return Auth::user()->disabled_at
                ? $this->sendDisabledResponse()
                : $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function attemptLoginAlerj(Request $request)
    {
        return $this->authentication->attempt($request, $request->filled('remember'));
    }

    private function sendDisabledResponse()
    {
        Auth::logout();

        return redirect()->route('errors.user-disabled');
    }

    function authenticated(Request $request, $user)
    {
        $user->last_login_at = Carbon::now();
        $user->save();
    }
}
