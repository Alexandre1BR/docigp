<?php

namespace App\Http\Controllers\Api;

use App\Data\Repositories\EntryDocuments as EntryDocumentsRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\EntryCommentDelete;
use App\Http\Requests\EntryCommentStore;
use App\Data\Repositories\EntryComments as EntryCommentsRepository;
use App\Http\Requests\EntryCommentUpdate;
use Illuminate\Http\Request;

class EntryComments extends Controller
{
    /**
     * Get all data
     *
     * @param $congressmanId
     * @param $congressmanBudgetId
     * @return array
     */
    public function all($congressmanId, $congressmanBudgetId, $entryId)
    {
        return app(EntryCommentsRepository::class)->allFor(
            $congressmanId,
            $congressmanBudgetId,
            $entryId
        );
    }

    /**
     * Store
     *
     * @param \App\Http\Requests\EntryCommentStore $request
     * @param $congressmanId
     * @param $congressmanBudgetId
     * @param $entryId
     * @return mixed
     */
    public function store(
        EntryCommentStore $request,
        $congressmanId,
        $congressmanBudgetId,
        $entryId
    ) {
        return app(EntryCommentsRepository::class)
            ->setEntryId($entryId)
            ->setData($request->all())
            ->store();
    }

    public function update(
        EntryCommentUpdate $request,
        $congressmanId,
        $congressmanBudgetId,
        $entryId,
        $entryCommentId
    ) {
        return app(EntryCommentsRepository::class)
            ->setEntryId($entryId)
            ->setData($request->all())
            ->update($entryCommentId);
    }

    public function delete(
        EntryCommentDelete $request,
        $congressmanId,
        $congressmanBudgetId,
        $entryId,
        $entryCommentId
    ) {
        return app(EntryCommentsRepository::class)->delete($entryCommentId);
    }

    public function audits(
        Request $request,
        $congressmanId,
        $congressmanBudgetId,
        $entryId,
        $entryCommentId
    ) {
        return app(EntryCommentsRepository::class)->audits($entryCommentId);
    }
}
