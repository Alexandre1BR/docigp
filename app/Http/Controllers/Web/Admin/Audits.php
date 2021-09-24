<?php

namespace App\Http\Controllers\Web\Admin;

use App\Models\Audit;
use App\Http\Controllers\Controller;

class Audits extends Controller
{
    //    public function activityTransform($audits)
    //    {
    //        $audits->getCollection()->transform(function ($item, $key) {
    //            $item->
    //            return $item;
    //        });
    //
    //        dd($audits->all());
    //    }

    public function index()
    {
        return $this->view('admin.audits.index')->with(
            'audits',
            Audit
                //            where('url', 'not like', '%/livewire/message/%')->
                ::where('url', 'not like', '%users/per-page%')
                ->where('auditable_type', 'not like', '%\ChangeUnread%')
                ->where('auditable_type', 'not like', '%\File%')
                ->where('auditable_type', 'not like', '%\AttachedFile%')
                ->orderBy('created_at', 'desc')
                ->paginate()
        );
    }
}
