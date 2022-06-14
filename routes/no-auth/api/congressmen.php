<?php

use App\Http\Controllers\Api\CongressmanBudgets;
use App\Http\Controllers\Api\Congressmen;
use App\Http\Controllers\Api\Entries;
use App\Http\Controllers\Api\EntryComments;
use App\Http\Controllers\Api\EntryDocuments;

Route::group(['prefix' => '/congressmen'], function () {
    Route::get('/', [Congressmen::class,'all'])->name('congressmen.all');

    Route::post('/{id}/mark-as-read', [Congressmen::class,'markAsRead'])->name(
        'congressmen.mark-as-read'
    );

    Route::group(['prefix' => '/{congressmanId}/budgets'], function () {
        Route::get('/', [CongressmanBudgets::class,'all'])->name(
            'congressmen.budgets.all'
        );

        Route::group(
            ['prefix' => '/{congressmanBudgetId}/entries'],
            function () {
                Route::get('/', [Entries::class,'all'])->name(
                    'congressmen.budgets.entries.all'
                );

                Route::group(['prefix' => '/{entryId}'], function () {
                    Route::group(['prefix' => '/documents'], function () {
                        Route::get('/', [EntryDocuments::class,'all'])->name(
                            'congressmen.budgets.entries.documents.all'
                        );
                    });

                    Route::group(['prefix' => '/comments'], function () {
                        Route::get('/', [EntryComments::class,'all'])->name(
                            'congressmen.budgets.entries.comments.all'
                        );
                    });
                });
            }
        );
    });
});
