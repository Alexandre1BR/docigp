<?php

use App\Data\Repositories\Entries;
use App\Data\Repositories\Users as UsersRepository;
use App\Data\Models\EntryDocument as EntryDocumentModel;
use Illuminate\Database\Eloquent\Factory;

//$factory->define(EntryDocumentModel::class, function () {
//    return [
//        'entry_id' => app(Entries::class)->randomElement()->id,
//        'created_by_id' => app(UsersRepository::class)->randomElement()->id,
//        'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
//    ];
//});


class EntryDocumentFactory extends Factory{

    protected $model = EntryDocumentModel::class;

    public function definition(){
        return [
            'entry_id' => app(Entries::class)->randomElement()->id,
            'created_by_id' => app(UsersRepository::class)->randomElement()->id,
            'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
        ];
    }
}
