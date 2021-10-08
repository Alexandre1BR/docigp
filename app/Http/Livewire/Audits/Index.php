<?php

namespace App\Http\Livewire\Audits;

use App\Data\Repositories\Audits as AuditsRepository;
use App\Http\Livewire\BaseIndex;
use App\Models\User;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends BaseIndex
{
    use WithPagination;

    protected $orderByField = 'created_at';
    protected $orderByDirection = 'desc';

    protected $repository = AuditsRepository::class;

    protected $refreshFields = ['user_id', 'searchString'];
    public $pageSize = 8;

    public $user_id = null;
    public $created_at_start = null;
    public $created_at_end = null;
    public $searchString = null;

    public $searchFields = [
        'audits.new_values' => 'text',
        'audits.old_values' => 'text',
    ];

    public function additionalFilterQuery($query)
    {
        return $query
            ->where('url', 'not like', '%users/per-page%')
            ->where('auditable_type', 'not like', '%\ChangeUnread%')
            ->where('auditable_type', 'not like', '%\File%')
            ->where('auditable_type', 'not like', '%\AttachedFile%')
            ->when($this->user_id, function ($query) {
                return $query->where('user_id', $this->user_id);
            })
            ->when($this->created_at_start, function ($query) {
                return $query->where('created_at', '>=', Carbon::create($this->created_at_start));
            })
            ->when($this->created_at_end, function ($query) {
                return $query->where(
                    'created_at',
                    '<=',
                    Carbon::create($this->created_at_end)->endOfDay()
                );
            });
    }

    public function render()
    {
        return view('livewire.audits.index')->with([
            'audits' => $this->filter(),
            'users' => User::orderBy('name', 'asc')->get(),
        ]);
    }
}
