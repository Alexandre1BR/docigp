<?php

namespace App\Http\Livewire\Audits;

use App\Data\Repositories\Audits as AuditsRepository;
use App\Http\Livewire\BaseIndex;
use App\Data\Repositories\Users as UsersRepository;
use App\Models\User;
use Livewire\WithPagination;

class Index extends BaseIndex
{
    use WithPagination;

    protected $orderByField = 'created_at';
    protected $orderByDirection = 'desc';

    protected $repository = AuditsRepository::class;

    protected $refreshFields = ['user_id'];
    public $searchFields = [];
    public $pageSize = 8;

    public $user_id = '';

    public function additionalFilterQuery($query)
    {
        return $query
            ->where('url', 'not like', '%users/per-page%')
            ->where('auditable_type', 'not like', '%\ChangeUnread%')
            ->where('auditable_type', 'not like', '%\File%')
            ->where('auditable_type', 'not like', '%\AttachedFile%')
            ->when($this->user_id, function ($query) {
                return $query->where('user_id', $this->user_id);
            });
    }

    public function render()
    {
        return view('livewire.audits.index')->with([
            'audits' => $this->filter(),
            'users' => User::all(),
        ]);
    }
}
