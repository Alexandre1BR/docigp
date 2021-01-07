<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     *
     * @return array
     */
    public function getSuccessMessage($message = 'Gravado com sucesso')
    {
        return ['status' => $message];
    }

    public function view($name)
    {
        return view($name)
            ->with('search', request('search'))
            ->with('query', request()->get('query'));
    }
}
