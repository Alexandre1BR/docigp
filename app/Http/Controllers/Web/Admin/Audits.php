<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Audit;
use App\Http\Controllers\Controller;

class Audits extends Controller
{
    public function index()
    {
        return $this->view('admin.audits.index')->with(
            'audits',
            Audit::orderBy('created_at', 'desc')->get()
        );
    }
}
