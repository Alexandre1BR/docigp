<?php

use App\Models\Congressman;

use App\Models\CongressmanBudget;
use App\Data\Repositories\Congressmen as CongressmenRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixCongressmanBudgetValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CongressmanBudget::disableEvents();

        //Carlo Caiado fev/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(742)){
            $congressmenBudget->value = 16091.98;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado ');
        }else{
            dump('Não foi encontrado o Orçamento 742');
        }

        //Carlo Caiado jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(672)){
            $congressmenBudget->value = 16091.98;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 672');
        }

        //Márcio Gauberto jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(704)){
            $congressmenBudget->value = 6704.99;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 704');
        }

        //Márcio Gauberto fev/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(774)){
            $congressmenBudget->value = 6704.99;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 774');
        }

        //Rosane Felix jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(717)){
            $congressmenBudget->value = 20114.98;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 717');
        }

        //Rosane Felix jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(787)){
            $congressmenBudget->value = 20114.98;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 787');
        }

        CongressmanBudget::enableEvents();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CongressmanBudget::disableEvents();

        //Carlo Caiado fev/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(742)){
            $congressmenBudget->value = 16091.99;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado ');
        }else{
            dump('Não foi encontrado o Orçamento 742');
        }

        //Carlo Caiado jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(672)){
            $congressmenBudget->value = 16091.99;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 672');
        }

        //Márcio Gauberto jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(704)){
            $congressmenBudget->value = 6705.00;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 704');
        }

        //Márcio Gauberto fev/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(774)){
            $congressmenBudget->value = 6705.00;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 774');
        }

        //Rosane Felix jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(717)){
            $congressmenBudget->value = 20114.99;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 717');
        }

        //Rosane Felix jan/2020
        if ($congressmenBudget = CongressmanBudget::withoutGlobalScopes()->find(787)){
            $congressmenBudget->value = 20114.99;
            $congressmenBudget->save();
            dump('O Orçamento ' . $congressmenBudget->id . ' foi atualizado');
        }else{
            dump('Não foi encontrado o Orçamento 787');
        }

        CongressmanBudget::enableEvents();
    }
}
