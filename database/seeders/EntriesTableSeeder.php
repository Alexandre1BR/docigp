<?php

namespace Database\Seeders;

use App\Support\Constants;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use App\Models\Congressman;
use App\Models\CongressmanBudget;
use App\Models\Entry as EntryModel;
use App\Models\EntryDocument as EntryDocumentModel;

class EntriesTableSeeder extends Seeder
{
    /**
     *
     *
     * @return void
     */
    public function run()
    {
        EntryModel::truncate();
        EntryDocumentModel::truncate();

        Congressman::disableGlobalScopes();

        Congressman::whereIn('id', range(1, 10))
            ->get()
            ->each(function (Congressman $congressman) {
                $this->seedEntries($congressman);
            });
    }

    private function seedEntries($congressman)
    {
        $congressman->congressmanBudgets->each(function (
            CongressmanBudget $congressmanBudget
        ) use ($congressman) {
            $entry = factory(EntryModel::class, 1)->create([
                'to' => $congressman->name,
                'object' => 'Crédito em conta-corrente',
                'provider_id' => Constants::ALERJ_PROVIDER_ID,
                'cost_center_id' => Constants::COST_CENTER_CREDIT_ID,
                'date' => $congressmanBudget->budget->date->startOfMonth(),
                'value' => ($value = app(Faker::class)->randomFloat(
                    2,
                    0.1,
                    26000
                )),
                'congressman_budget_id' => $congressmanBudget->id,

                'entry_type_id' => Constants::ENTRY_TYPE_ALERJ_DEPOSIT_ID
            ]);

            $entry[0]->congressmanBudget->percentage =
                ($value / $entry[0]->congressmanBudget->budget->value) * 100;

            $entry[0]->congressmanBudget->save();

            foreach (range(1, rand(1, 6)) as $counter) {
                $entry = factory(EntryModel::class)->create([
                    'date' => faker()->dateTimeBetween(
                        $congressmanBudget->budget->date->startOfMonth(),
                        $congressmanBudget->budget->date->endOfMonth(),
                        $timezone = null
                    ),

                    'value' => -app(Faker::class)->randomFloat(2, 0.1, 1000),

                    'congressman_budget_id' => $congressmanBudget->id
                ]);

                //                factory(EntryDocumentModel::class, rand(0, 8))->create([
                //                    'entry_id' => $entry->id,
                //                ]);
            }

            $congressmanBudget->updateTransportEntries();
        });
    }
}
