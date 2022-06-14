<?php

namespace App\Data\Repositories;

use App\Models\User;
use App\Support\Constants;
use App\Data\Repositories\Congressmen as CongressmenRepository;
use Illuminate\Support\Facades\Hash;
use DB;

class Users extends Repository
{
    /**
     * @var string
     */
    protected $model = User::class;

    /**
     * @param $email
     *
     * @return mixed
     */
    public function findUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findUserById($id)
    {
        return User::find($id);
    }

    /**
     * @param $request
     * @param $remember
     *
     * @return User
     */
    public function updateLoginUser($request, $remember, $response)
    {
        try {
            $credentials = extract_credentials($request);

            if (
                is_null(
                    $user = $this->findUserByEmail(
                        $email = "{$credentials['username']}@alerj.rj.gov.br"
                    )
                )
            ) {
                $user = new User();

                $user->username = $credentials['username'];

                $user->email = $email;
            }

            $user->name = $response['data']['name'][0];

            $user->password = Hash::make($credentials['password']);

            $user->save();
        } catch (\Exception $exception) {
            report($exception);

            return null;
        }

        return $user;
    }

    /**
     * @return mixed
     */
    public function notifiables()
    {
        return User::where('all_notifications', true)->get();
    }

    public function updatePerPage($user, $size)
    {
        $user->per_page = $size;

        $user->save();

        return $user;
    }

    public function associateCongressmanWithUser($congressman_id, $request)
    {
        $this->model = $this->findUserByEmail($request['email']);

        $congressman = app(CongressmenRepository::class)->findById($congressman_id);

        $this->model['congressman_id'] = $congressman_id;
        $this->model['department_id'] = $congressman->department->id ?? null;

        $this->model->save();

        $this->model->assign(Constants::ROLE_CONGRESSMAN);

        return $this->model;
    }

    public function searchFromRequest($search = null)
    {
        $search = is_null($search)
            ? collect()
            : collect(explode(' ', $search))->map(function ($item) {
                return strtolower($item);
            });

        $columns = collect([
            'name' => 'string',
            'email' => 'string',
            'username' => 'string',
        ]);

        $query = $this->model::query();

        $search->each(function ($item) use ($columns, $query) {
            $columns->each(function ($type, $column) use ($query, $item) {
                if ($type === 'string') {
                    $query->orWhere(DB::raw("lower({$column})"), 'like', '%' . $item . '%');
                } else {
                    if ($this->isDate($item)) {
                        $query->orWhere($column, '=', $item);
                    }
                }
            });
        });

        return $this->makeResultForSelect($query->orderBy('name')->get());
    }

    public function getSystemModel()
    {
        return \Cache::remember('getSystemModel', 15, function () {
            return User::where('email', 'system@docigp.alerj.rj.gov.br')->first();
        });
    }

    public function loginAsSystem()
    {
        if ($systemUser = $this->getSystemModel()) {
            auth()->login($systemUser);
        }
    }
}
