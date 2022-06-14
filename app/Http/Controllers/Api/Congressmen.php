<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Data\Repositories\Congressmen as CongressmenRepository;

class Congressmen extends Controller
{
    /**
     * Get all data
     *
     * @return array
     */
    public function all()
    {
        return app(CongressmenRepository::class)->all();
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function availableCongressmen()
    {
        return app(CongressmenRepository::class)->getAvailableCongressmen();
    }

    public function markAsRead($id)
    {
        return app(CongressmenRepository::class)->markAsRead($id);
    }
}
