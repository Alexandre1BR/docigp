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

    protected $refreshFields = ['user_id', 'searchString', 'created_at_start', 'created_at_end'];
    public $pageSize = 5;

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
        $query = $query
            ->whereNotIn('auditable_type', [
                'App\\Models\\ChangeUnread',
                'App\\Models\\File',
                'App\\Models\\\AttachedFile',
            ])
            ->where('url', 'not like', '%users/per-page%')
            ->when($this->user_id, function ($query) {
                return $query->where('user_id', $this->user_id);
            })
            ->when($this->created_at_start, function ($query) {
                return $query->where(
                    'created_at',
                    '>=',
                    Carbon::create($this->created_at_start)->startOfDay()
                );
            })
            ->when($this->created_at_end, function ($query) {
                return $query->where(
                    'created_at',
                    '<=',
                    Carbon::create($this->created_at_end)->endOfDay()
                );
            });

        return $query;
    }

    public function mount()
    {
        $this->created_at_start = now()
            ->startOfMonth()
            ->subDays(30)
            ->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.audits.index')->with([
            'audits' => $this->filter(),
            'users' => User::orderBy('name', 'asc')->get(),
        ]);
    }
}
