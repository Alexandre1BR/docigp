<?php

namespace App\Services\Authentication;

use App\Data\Repositories\Users;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Request\RemoteRequest;
use App\Data\Repositories\Users as UsersRepository;
use Illuminate\Support\Facades\Log;

class Service
{
    const LOGIN_URL = 'https://apiportal.alerj.rj.gov.br/api/v1.0/ldap/0IYFFiMHuUr1sYo6wEtjUsJQ7Zicg33SMuvtrFk9yEgwrORmblNSMdpTH0ZTRKX2BhADIusjXHInHW3cspyosOoNrbd5jObK5Uoh/login';
    const USER_INFO_URL = 'https://apiportal.alerj.rj.gov.br/api/v1.0/ldap/0IYFFiMHuUr1sYo6wEtjUsJQ7Zicg33SMuvtrFk9yEgwrORmblNSMdpTH0ZTRKX2BhADIusjXHInHW3cspyosOoNrbd5jObK5Uoh/user';
    const PERMISSIONS_URL = 'https://apiportal.alerj.rj.gov.br/api/v1.0/adm-user/K7k8H95loFpTH0ZTRKX2BhADIusjXHInHW3cspyosOoNrbd5jOG3pd61F4d6fg584Gg5h4DSjui1k/permissions';
    const PROFILES_URL = 'http://apiportal.alerj.rj.gov.br/api/v1.0/adm-user/K7k8H95loFpTH0ZTRKX2BhADIusjXHInHW3cspyosOoNrbd5jOG3pd61F4d6fg584Gg5h4DSjui1k/profiles';

    /**
     * @var Guzzle
     */
    protected $guzzle;

    protected $remoteRequest;

    /**
     * @var Users
     */
    protected $usersRepository;

    public function __construct(Users $usersRepository, RemoteRequest $remoteRequest)
    {
        $this->usersRepository = $usersRepository;

        $this->remoteRequest = $remoteRequest;
    }

    public function attempt($request, $remember)
    {
        $response = $this->loginRequest($request);

        return $response['success'] && $this->updateLoginUser($request, $remember, $response);
    }

    /**
     * @param $request
     * @return mixed
     */
    protected function extractCredencialsForUsername($request)
    {
        $credentials = extract_credentials($request);

        if (isset($credentials['username'])) {
            return $credentials['username'];
        }

        return null;
    }

    protected function extractUsernameFromEmail($email)
    {
        if (($pos = strpos($email, '@')) === false) {
            return $email;
        }

        return substr($email, 0, $pos);
    }

    protected function instantiateGuzzle()
    {
        $this->guzzle = new Guzzle();
    }

    /**
     * @param $request
     *
     * @return mixed
     */
    protected function loginRequest($request)
    {
        if (config('auth.authentication.mock')) {
            return $this->mockedAuthentication($request);
        }

        try {
            $response = $this->remoteRequest->post(
                static::LOGIN_URL,
                extract_credentials($request)
            );
            return $response;
        } catch (\Exception $exception) {
            //Timeout no login
            $usersRepository = app(UsersRepository::class);
            $user = $usersRepository->findByUsername(
                $this->extractCredencialsForUsername($request)
            );

            if (is_null($user)) {
                // Sistema de login fora do ar e usuário novo
                Log::error(
                    'O usuário ' .
                        extract_credentials($request)['username'] .
                        ' tentou fazer login, mas não foi possível pois o sistema remoto autenticação está fora do ar e não há histórico do usuário no banco de dados'
                );
                abort(403);
            } else {
                //Usuário já cadastrado
                if (Hash::check(extract_credentials($request)['password'], $user->password)) {
                    //Credenciais de login conferem com as salvas no banco
                    return $this->successAuthentication($user);
                } else {
                    //Credenciais de login não conferem com as salvas no banco
                    return $this->failedAuthentication();
                }
            }
        }
    }

    /**
     * @param $request
     * @param $response
     * @param $remember
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function loginUser($request, $response, $remember)
    {
        if ($success = $response['success']) {
            $success = $this->usersRepository->loginUser($request, $remember);

            if (!$success) {
                return false;
            }

            $permissions = app(Authorization::class)->getUserPermissions(
                $this->extractCredencialsForUsername($request)
            );

            $this->usersRepository->updateCurrentUserTypeViaPermissions($permissions);
        }

        return $success;
    }

    /**
     * @param $user
     *
     * @return array
     */
    protected function successAuthentication($user)
    {
        return [
            'success' => true,
            'code' => 200,
            'message' => null,
            'data' => [
                'name' => [$user->name],
                'email' => [$user->username . '@alerj.rj.gov.br'],
                'memberof' => [
                    'CN=ProjEsp,OU=SDGI,OU=Departmentos,OU=ALERJ,DC=alerj,DC=gov,DC=br',
                    'CN=SDGI,OU=SDGI,OU=Departmentos,OU=ALERJ,DC=alerj,DC=gov,DC=br',
                ],
                'description' => ['matricula: N/C'],
            ],
        ];
    }

    /**
     * @param $credentials
     *
     * @return array
     */
    protected function mockedAuthentication($credentials)
    {
        return [
            'success' => true,
            'code' => 200,
            'message' => null,
            'data' => [
                'name' => [$credentials['username']],
                'email' => [$credentials['username'] . '@alerj.rj.gov.br'],
                'memberof' => [
                    'CN=ProjEsp,OU=SDGI,OU=Departmentos,OU=ALERJ,DC=alerj,DC=gov,DC=br',
                    'CN=SDGI,OU=SDGI,OU=Departmentos,OU=ALERJ,DC=alerj,DC=gov,DC=br',
                ],
                'description' => ['matricula: N/C'],
            ],
        ];
    }

    /**
     *
     * @return array
     */
    protected function failedAuthentication()
    {
        return [
            'success' => false,
            'code' => 401,
            'message' => 'Attempt failed.',
            'data' => [],
        ];
    }

    /**
     * @param $request
     * @param $remember
     * @param $response
     * @return boolean
     */
    private function updateLoginUser($request, $remember, $response): bool
    {
        $user = $this->usersRepository->updateLoginUser($request, $remember, $response);

        if (filled($user)) {
            Auth::login($user, $remember);
        }

        return filled($user);
    }

    public function emailExists($email)
    {
        return isset($this->userInfoByEmail($email)['name']);
    }

    public function userInfoByUsername($username)
    {
        try {
            $response = $this->remoteRequest->post(static::USER_INFO_URL, [
                'username' => $username,
            ]);
            return $response;
        } catch (\Exception $exception) {
            Log::error(
                'Tentativa de procurar login de ' .
                    $username .
                    ' falhou pois o sistema remoto autenticação está fora do ar'
            );
            return $this->failedAuthentication();
        }
    }

    public function userInfoByEmail($email)
    {
        preg_match('/(.*?)@(.*)/', $email, $output_array);
        if ($output_array[2] != 'alerj.rj.gov.br') {
            return $this->failedAuthentication();
        }
        return $this->userInfoByUsername($output_array[1]);
    }
}
