<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\EntryDocument;

class FixOtherAttachedDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        EntryDocument::disableMarking();

        //Foram desvinculados antes de Outubro
        //Carlos Macedo      | EXTRATO - MAIO.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(741)) {
            $entryDocument->entry_id = 2861;
            $entryDocument->published_at = $entryDocument->published_by_id = null;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 741 não foi encontrado");
        }

        //Carlos Macedo      | EXTRATO - MAIO 2.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(743)) {
            $entryDocument->entry_id = 2861;
            $entryDocument->published_at = $entryDocument->published_by_id = null;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 743 não foi encontrado");
        }

        //Dr. Deodalto      | Extrato mês de JUNHO (DEODALTO).pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(3156)) {
            $entryDocument->entry_id = 2871;
            $entryDocument->published_at = $entryDocument->published_by_id = null;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 3156 não foi encontrado");
        }

        //Dr. Deodalto      | extrato_2019-07-04_02_46_21.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(966)) {
            $entryDocument->entry_id = 2871;
            $entryDocument->published_at = $entryDocument->published_by_id = null;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 966 não foi encontrado");
        }

        EntryDocument::enableMarking();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        EntryDocument::disableMarking();

        //Foram desvinculados antes de Outubro
        //Carlos Macedo      | EXTRATO - MAIO.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(741)) {
            $entryDocument->entry_id = 567;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 741 não foi encontrado");
        }

        //Carlos Macedo      | EXTRATO - MAIO 2.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(743)) {
            $entryDocument->entry_id = 567;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 743 não foi encontrado");
        }

        //Dr. Deodalto      | Extrato mês de JUNHO (DEODALTO).pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(3156)) {
            $entryDocument->entry_id = 1124;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 3156 não foi encontrado");
        }

        //Dr. Deodalto      | extrato_2019-07-04_02_46_21.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(966)) {
            $entryDocument->entry_id = 700;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 966 não foi encontrado");
        }

        EntryDocument::enableMarking();
    }
}
