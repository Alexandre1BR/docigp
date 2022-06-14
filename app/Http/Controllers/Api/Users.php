<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Data\Repositories\Users as UsersRepository;

class Users extends Controller
{
    public function perPage($size)
    {
        return auth()->user()
            ? app(UsersRepository::class)->updatePerPage(auth()->user(), $size)
            : null;
    }
}
