<?php

use App\Models\Entry as Entry;
use App\Data\Repositories\EntryTypes;
use App\Data\Repositories\Providers;
use App\Data\Repositories\CostCenters;
use App\Models\Entry as EntryModel;
use App\Data\Repositories\CongressmanBudgets;
use App\Data\Repositories\Users as UsersRepository;
use App\Support\Constants;
use Illuminate\Database\Eloquent\Factory;

//$factory->define(EntryModel::class, function () {
//    EntryModel::disableGlobalScopes();
//    $array =  [
//        'date' => faker()->dateTimeBetween(
//            $startDate = \Carbon\Carbon::now()->startOfMonth(),
//            $endDate = \Carbon\Carbon::now()->endOfMonth(),
//            $timezone = null
//        ),
//        'value' => faker()->randomFloat(2, 0.1, 1000),
//        'object' => faker()->text(30),
//        'to' => faker()->name,
//
//        'entry_type_id' => ($entry_type = app(EntryTypes::class)->randomElement(
//            [
//                Constants::ENTRY_TYPE_ALERJ_DEPOSIT_ID,
//                Constants::ENTRY_TYPE_TRANSPORT_ID,
//            ]
//        ))->id,
//
//        'document_number' => $entry_type->document_number_required
//            ? faker()->numberBetween(1111, 9999)
//            : null,
//
//        'provider_id' => app(Providers::class)->randomElement([
//            Constants::ALERJ_PROVIDER_ID,
//        ])->id,
//
//        'cost_center_id' => app(CostCenters::class)->randomElement([
//            Constants::COST_CENTER_CREDIT_ID,
//            Constants::COST_CENTER_TRANSPORT_FROM_PREVIOUS_ID,
//            Constants::COST_CENTER_TRANSPORT_TO_NEXT_ID,
//        ])->id,
//
//        'congressman_budget_id' => app(
//            CongressmanBudgets::class
//        )->randomElement()->id,
//        'verified_at' => ($verified = rand(0, 1) ? faker()->date : null),
//        'analysed_at' => ($analysed =
//            $verified && rand(0, 1) ? faker()->date : null),
//        'published_at' => ($published =
//            $analysed && rand(0, 1) ? faker()->date : null),
//        'verified_by_id' => $verified
//            ? app(UsersRepository::class)->randomElement()->id
//            : null,
//        'analysed_by_id' => $analysed
//            ? app(UsersRepository::class)->randomElement()->id
//            : null,
//        'published_by_id' => $published
//            ? app(UsersRepository::class)->randomElement()->id
//            : null,
//        'created_by_id' => app(UsersRepository::class)->randomElement()->id,
//        'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
//    ];
//    return $array;
//    EntryModel::enableGlobalScopes();
//});

class EntryFactory extends Factory{

    protected $model = Entry::class;

    public function definition()
    {
            EntryModel::disableGlobalScopes();
    $array =  [
        'date' => faker()->dateTimeBetween(
            $startDate = \Carbon\Carbon::now()->startOfMonth(),
            $endDate = \Carbon\Carbon::now()->endOfMonth(),
            $timezone = null
        ),
        'value' => faker()->randomFloat(2, 0.1, 1000),
        'object' => faker()->text(30),
        'to' => faker()->name,

        'entry_type_id' => ($entry_type = app(EntryTypes::class)->randomElement(
            [
                Constants::ENTRY_TYPE_ALERJ_DEPOSIT_ID,
                Constants::ENTRY_TYPE_TRANSPORT_ID,
            ]
        ))->id,

        'document_number' => $entry_type->document_number_required
            ? faker()->numberBetween(1111, 9999)
            : null,

        'provider_id' => app(Providers::class)->randomElement([
            Constants::ALERJ_PROVIDER_ID,
        ])->id,

        'cost_center_id' => app(CostCenters::class)->randomElement([
            Constants::COST_CENTER_CREDIT_ID,
            Constants::COST_CENTER_TRANSPORT_FROM_PREVIOUS_ID,
            Constants::COST_CENTER_TRANSPORT_TO_NEXT_ID,
        ])->id,

        'congressman_budget_id' => app(
            CongressmanBudgets::class
        )->randomElement()->id,
        'verified_at' => ($verified = rand(0, 1) ? faker()->date : null),
        'analysed_at' => ($analysed =
            $verified && rand(0, 1) ? faker()->date : null),
        'published_at' => ($published =
            $analysed && rand(0, 1) ? faker()->date : null),
        'verified_by_id' => $verified
            ? app(UsersRepository::class)->randomElement()->id
            : null,
        'analysed_by_id' => $analysed
            ? app(UsersRepository::class)->randomElement()->id
            : null,
        'published_by_id' => $published
            ? app(UsersRepository::class)->randomElement()->id
            : null,
        'created_by_id' => app(UsersRepository::class)->randomElement()->id,
        'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
    ];
    return $array;
    EntryModel::enableGlobalScopes();
    }
}
