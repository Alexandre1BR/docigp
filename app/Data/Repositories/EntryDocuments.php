<?php

namespace App\Data\Repositories;

use App\Data\Traits\RepositoryActionable;
use App\Data\Repositories\Files as FilesRepository;
use App\Models\EntryDocument as EntryDocument;

class EntryDocuments extends Repository
{
    use RepositoryActionable;

    /**
     * @var string
     */
    protected $model = EntryDocument::class;

    /**
     * @var integer
     */
    private $entryId;

    public function allFor($congressmanId, $congressmanBudgetId, $entryId)
    {
        return $this->applyFilter(
            $this->newQuery()
                ->join('entries', 'entries.id', 'entry_documents.entry_id')
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
                ->where('entry_documents.entry_id', $entryId)
        );
    }

    /**
     * @param \App\Models\File $physicalFile
     * @return bool
     */
    private function documentWasAlreadyUploaded(
        \App\Models\File $physicalFile
    ): bool {
        return $this->getEntry()->documents->reduce(function (
            $carry,
            $document
        ) use ($physicalFile) {
            return $carry ||
                $document->attachedFile->file->id == $physicalFile->id;
        },
        false);
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
     * @return \App\Data\Repositories\EntryDocuments
     */
    public function setEntryId($entryId): EntryDocuments
    {
        $this->entryId = $entryId;

        return $this;
    }

    public function store()
    {
        $physicalFile = app(FilesRepository::class)->storePhysicalFile(
            $uploadedFile = $this->data['file']
        );

        if ($this->documentWasAlreadyUploaded($physicalFile)) {
            return;
        }

        app(FilesRepository::class)->createAttachment(
            EntryDocument::create([
                'entry_id' => $this->getEntry()->id,
            ]),
            $physicalFile,
            $uploadedFile->getClientOriginalName()
        );
    }
}
