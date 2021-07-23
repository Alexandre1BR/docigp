<?php

namespace App\Data\Repositories;

use App\Data\Traits\RepositoryActionable;
use App\Data\Repositories\Files as FilesRepository;
use App\Models\EntryComment as EntryComment;

class EntryComments extends Repository
{
    use RepositoryActionable;

    /**
     * @var string
     */
    protected $model = EntryComment::class;

    /**
     * @var integer
     */
    private $entryId;

    public function allFor($congressmanId, $congressmanBudgetId, $entryId)
    {
        return $this->applyFilter(
            $this->newQuery()
                ->join('entries', 'entries.id', 'entry_comments.entry_id')
                ->join(
                    'congressman_budgets',
                    'congressman_budgets.id',
                    'entries.congressman_budget_id'
                )
                ->join(
                    'congressman_legislatures',
                    'congressman_legislatures.id',
                    'congressman_budgets.congressman_legislature_id'
                )
                ->where(
                    'congressman_legislatures.congressman_id',
                    $congressmanId
                )
                ->where('congressman_budgets.id', $congressmanBudgetId)
                ->where('entry_comments.entry_id', $entryId)
        );
    }
    /**
     * @return mixed
     */
    protected function getEntry()
    {
        return app(Entries::class)->findById($this->entryId);
    }

    /**
     * @param mixed $entryId
     * @return \App\Data\Repositories\EntryComments
     */
    public function setEntryId($entryId): EntryComments
    {
        $this->entryId = $entryId;

        return $this;
    }

    public function store()
    {
        $this->data['entry_id'] = $this->entryId;
        $this->storeFromArray($this->data);
    }
}
