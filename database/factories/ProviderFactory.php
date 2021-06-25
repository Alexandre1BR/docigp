<?php

use App\Services\CpfCnpj\CpfCnpj;
use App\Models\Provider as ProviderModel;
use App\Data\Repositories\Users as UsersRepository;
use Illuminate\Database\Eloquent\Factory;

//$factory->define(ProviderModel::class, function () {
//    return [
//        'cpf_cnpj' => ($code =
//            ($rand = rand(1, 2)) === 2
//                ? CpfCnpj::generateCpf()
//                : CpfCnpj::generateCnpj()),
//
//        'type' => CpfCnpj::isCpf($code) ? 'PF' : 'PJ',
//
//        'name' => $rand === 2 ? faker()->name : faker()->company,
//
//        'created_by_id' => app(UsersRepository::class)->randomElement()->id,
//
//        'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
//    ];
//});

class ProviderFactory extends Factory{

    protected $model = ProviderModel::class;

    public function definition()
    {
        return [
        'cpf_cnpj' => ($code =
            ($rand = rand(1, 2)) === 2
                ? CpfCnpj::generateCpf()
                : CpfCnpj::generateCnpj()),

        'type' => CpfCnpj::isCpf($code) ? 'PF' : 'PJ',

        'name' => $rand === 2 ? faker()->name : faker()->company,

        'created_by_id' => app(UsersRepository::class)->randomElement()->id,

        'updated_by_id' => app(UsersRepository::class)->randomElement()->id,
        ];
    }
}
