<?php

use App\Http\Controllers\Api\CongressmanBudgets;
use App\Http\Controllers\Api\Congressmen as Congressmen;
use App\Http\Controllers\Api\Entries as Entries;
use App\Http\Controllers\Api\EntryComments;
use App\Http\Controllers\Api\EntryDocuments;

Route::group(['prefix' => '/congressmen'], function () {
    Route::post('/{id}', [Congressmen::class, 'update'])->name('congressmen.update');

    Route::post('/', [Congressmen::class, 'store'])->name('congressmen.store');

    Route::group(['prefix' => '/{congressmanId}/budgets'], function () {
        Route::group(['prefix' => '/{congressmanBudgetId}'], function () {
            Route::post('/', [CongressmanBudgets::class, 'update'])->name(
                'congressmen.budgets.update'
            );

            Route::post('/close', [CongressmanBudgets::class, 'close'])->name(
                'congressmen.budgets.close'
            );

            Route::post('/reopen', [CongressmanBudgets::class, 'reopen'])->name(
                'congressmen.budgets.reopen'
            );

            Route::post('/analyse', [CongressmanBudgets::class, 'analyse'])->name(
                'congressmen.budgets.analyse'
            );

            Route::post('/unanalyse', [CongressmanBudgets::class, 'unanalyse'])->name(
                'congressmen.budgets.unanalyse'
            );

            Route::post('/publish', [CongressmanBudgets::class, 'publish'])->name(
                'congressmen.budgets.publish'
            );

            Route::post('/unpublish', [CongressmanBudgets::class, 'unpublish'])->name(
                'congressmen.budgets.unpublish'
            );

            Route::post('/deposit', [CongressmanBudgets::class, 'deposit'])->name(
                'congressmen.budgets.deposit'
            );
        });

        Route::post('/', [CongressmanBudgets::class, 'store'])->name('congressmen.budgets.store');

        Route::group(['prefix' => '/{congressmanBudgetId}/entries'], function () {
            Route::post('/', [Entries::class, 'store'])->name('entries.store');

            Route::get('/empty-refund-form', [Entries::class, 'emptyRefundForm'])->name(
                'entries.empty-refund-form'
            );

            Route::group(['prefix' => '/{entryId}'], function () {
                Route::post('/', [Entries::class, 'update'])->name('entries.update');

                Route::get('/audits', [Entries::class, 'audits'])->name(
                    'congressmen.budgets.entries.audits'
                );

                Route::post('/delete', [Entries::class, 'delete'])->name(
                    'congressmen.budgets.entries.delete'
                );

                Route::post('/verify', [Entries::class, 'verify'])->name(
                    'congressmen.budgets.entries.verify'
                );

                Route::post('/unverify', [Entries::class, 'unverify'])->name(
                    'congressmen.budgets.entries.unverify'
                );

                Route::post('/analyse', [Entries::class, 'analyse'])->name(
                    'congressmen.budgets.entries.analyse'
                );

                Route::post('/unanalyse', [Entries::class, 'unanalyse'])->name(
                    'congressmen.budgets.entries.unanalyse'
                );

                Route::post('/publish', [Entries::class, 'publish'])->name(
                    'congressmen.budgets.entries.publish'
                );

                Route::post('/unpublish', [Entries::class, 'unpublish'])->name(
                    'congressmen.budgets.entries.unpublish'
                );

                Route::group(['prefix' => '/documents'], function () {
                    Route::post('/', [EntryDocuments::class, 'store'])->name(
                        'congressmen.budgets.entries-documents.store'
                    );

                    Route::group(['prefix' => '/{documentId}'], function () {
                        Route::post('/publish', [EntryDocuments::class, 'publish'])->name(
                            'congressmen.budgets.entries-documents.publish'
                        );

                        Route::post('/unpublish', [EntryDocuments::class, 'unpublish'])->name(
                            'congressmen.budgets.entries-documents.unpublish'
                        );

                        Route::post('/verify', [EntryDocuments::class, 'verify'])->name(
                            'congressmen.budgets.entries-documents.verify'
                        );

                        Route::post('/unverify', [EntryDocuments::class, 'unverify'])->name(
                            'congressmen.budgets.entries-documents.unverify'
                        );

                        Route::post('/analyse', [EntryDocuments::class, 'analyse'])->name(
                            'congressmen.budgets.entries-documents.analyse'
                        );

                        Route::post('/unanalyse', [EntryDocuments::class, 'unanalyse'])->name(
                            'congressmen.budgets.entries-documents.unanalyse'
                        );

                        Route::post('/delete', [EntryDocuments::class, 'delete'])->name(
                            'congressmen.budgets.entries-documents.delete'
                        );

                        Route::get('/audits', [EntryDocuments::class, 'audits'])->name(
                            'congressmen.budgets.entries-documents.audits'
                        );
                    });
                });

                Route::group(['prefix' => '/comments'], function () {
                    Route::post('/', [EntryComments::class, 'store'])->name(
                        'congressmen.budgets.entries-comments.store'
                    );

                    Route::group(['prefix' => '/{commentId}'], function () {
                        Route::post('/', [EntryComments::class, 'update'])->name(
                            'congressmen.budgets.entries-comments.update'
                        );

                        Route::post('/delete', [EntryComments::class, 'delete'])->name(
                            'congressmen.budgets.entries-comments.delete'
                        );

                        Route::get('/audits', [EntryComments::class, 'audits'])->name(
                            'congressmen.budgets.entries-comments.audits'
                        );
                    });
                });
            });
        });
    });
});
