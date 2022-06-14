<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\EntryDocument;

class FixAttachedDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        EntryDocument::disableMarking();

        //Foram desvinculados de outubro para nov
        //Carlo Caiado      | Extrato_6171-139360-17-09-2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(4099)) {
            $entryDocument->entry_id = 2858;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 4099 não foi encontrado");
        }
        //Carlos Minc       | Extrato Mensal_Julho2019 (1).pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(3468)) {
            $entryDocument->entry_id = 2864;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 3468 não foi encontrado");
        }
        //Enfermeira Rejane | Extrato_6171-140673-29-10-2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(7016)) {
            $entryDocument->entry_id = 2874;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 7016 não foi encontrado");
        }
        //Mônica Francisco  | Extrato_maio2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(587)) {
            $entryDocument->entry_id = 2923;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 587 não foi encontrado");
        }
        //Márcio Gualberto  | Extrato Mensal_Junho2019.1.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(3041)) {
            $entryDocument->entry_id = 2911;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 3041 não foi encontrado");
        }

        //Foram desvinculados antes de outubro

        //Alana Passos      | Extrato01a30.06.DAP.Junho19.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(2647)) {
            $entryDocument->entry_id = 3007;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 2647 não foi encontrado");
        }

        //Alexandre Freitas | extrato_prestação contas AF. Junho 2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(769)) {
            $entryDocument->entry_id = 2846;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 769 não foi encontrado");
        }

        //Bruno Dauaire     | extrato bancario maio.19.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(1192)) {
            $entryDocument->entry_id = 2853;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 1192 não foi encontrado");
        }

        //Renata Souza      | Extrato bancário Agosto 2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(4103)) {
            $entryDocument->entry_id = 2932;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 4103 não foi encontrado");
        }

        //Vandro Familia    | Extrato  Maio.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(1277)) {
            $entryDocument->entry_id = 2964;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 1277 não foi encontrado");
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

        //Foram desvinculados de outubro para nov
        //Carlo Caiado      | Extrato_6171-139360-17-09-2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(4099)) {
            $entryDocument->entry_id = 1757;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 4099 não foi encontrado");
        }
        //Carlos Minc       | Extrato Mensal_Julho2019 (1).pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(3468)) {
            $entryDocument->entry_id = 1450;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 3468 não foi encontrado");
        }
        //Enfermeira Rejane | Extrato_6171-140673-29-10-2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(7016)) {
            $entryDocument->entry_id = 2752;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 7016 não foi encontrado");
        }
        //Mônica Francisco  | Extrato_maio2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(587)) {
            $entryDocument->entry_id = 475;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 587 não foi encontrado");
        }
        //Márcio Gualberto  | Extrato Mensal_Junho2019.1.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(3041)) {
            $entryDocument->entry_id = 1257;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 3041 não foi encontrado");
        }

        //Foram desvinculados antes de outubro

        //Alana Passos      | Extrato01a30.06.DAP.Junho19.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(2647)) {
            $entryDocument->entry_id = 637;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 2647 não foi encontrado");
        }

        //Alexandre Freitas | extrato_prestação contas AF. Junho 2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(769)) {
            $entryDocument->entry_id = 580;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 769 não foi encontrado");
        }

        //Bruno Dauaire     | extrato bancario maio.19.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(1192)) {
            $entryDocument->entry_id = 683;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 1192 não foi encontrado");
        }

        //Renata Souza      | Extrato bancário Agosto 2019.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(4103)) {
            $entryDocument->entry_id = 1639;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 4103 não foi encontrado");
        }

        //Vandro Familia    | Extrato  Maio.pdf
        if ($entryDocument = EntryDocument::withoutGlobalScopes()->find(1277)) {
            $entryDocument->entry_id = 750;
            $entryDocument->save();
            dump(
                "O documento {$entryDocument->id} foi desvinculado e vinculado ao lançamento {$entryDocument->entry_id}"
            );
        } else {
            dump("O documento 1277 não foi encontrado");
        }

        EntryDocument::enableMarking();
    }
}
