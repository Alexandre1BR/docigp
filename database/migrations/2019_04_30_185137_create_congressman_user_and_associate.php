<?php

use App\Models\User;
use App\Data\Repositories\Users;
use App\Services\DataSync\Service as DataSyncService;
use App\Support\Constants;
use Illuminate\Database\Migrations\Migration;

class CreateCongressmanUserAndAssociate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    const USERS = [
        'André Ceciliano' => 'andrececiliano@alerj.rj.gov.br',
        'Alana Passos' => 'alanapassos@alerj.rj.gov.br',
        'Alexandre Freitas' => 'alexandrefreitas@alerj.rj.gov.br',
        'Alexandre Knoploch' => 'alexandreknoplock@alerj.rj.gov.br',
        'Anderson Moraes' => 'andersonmoraes@alerj.rj.gov.br',
        'Bebeto' => 'bebeto@alerj.rj.gov.br',
        'Bruno Dauaire' => 'brunodauaire@alerj.rj.gov.br',
        'Carlos Macedo' => 'carlosmacedo@alerj.rj.gov.br',
        'Carlos Minc' => 'carlosminc@alerj.rj.gov.br',
        'Chicão Bulhões' => 'chicaobulhoes@alerj.rj.gov.br',
        'Chico Machado' => 'chicomachado@alerj.rj.gov.br',
        'Coronel Salema' => 'coronelsalema@alerj.rj.gov.br',
        'Dani Monteiro' => 'danimonteiro@alerj.rj.gov.br',
        'Danniel Librelon' => 'danniellibrelom@alerj.rj.gov.br',
        'Delegado Carlos Augusto' => 'delegadocarlosaugusto@alerj.rj.gov.br',
        'Dionisio Lins' => 'dionisiolins@alerj.rj.gov.br',
        'Dr. Deodalto' => 'drdeodalto@alerj.rj.gov.br',
        'Dr. Serginho' => 'drserginho@alerj.rj.gov.br',
        'Eliomar Coelho' => 'eliomarcoelho@alerj.rj.gov.br',
        'Enfermeira Rejane' => 'enfermeirarejane@alerj.rj.gov.br',
        'Fábio Silva' => 'fabiosilva@alerj.rj.gov.br',
        'Filippe Poubel' => 'filippepoubel@alerj.rj.gov.br',
        'Filipe Soares' => 'filipesoares@alerj.rj.gov.br',
        'Flavio Serafini' => 'flavioserafini@alerj.rj.gov.br',
        'Franciane Motta' => 'francianemotta@alerj.rj.gov.br',
        'Gil Vianna' => 'gilvianna@alerj.rj.gov.br',
        'Giovani Ratinho' => 'giovaniratinho@alerj.rj.gov.br',
        'Gustavo Schmidt' => 'gustavoschmidt@alerj.rj.gov.br',
        'Gustavo Tutuca' => 'gustavotutuca@alerj.rj.gov.br',
        'Jair Bittencourt' => 'jairbittencourt@alerj.rj.gov.br',
        'João Peixoto' => 'joaopeixoto@alerj.rj.gov.br',
        'Jorge Felippe Neto' => 'jorgefelippeneto@alerj.rj.gov.br',
        'Leo Vieira' => 'leovieira@alerj.rj.gov.br',
        'Lucinha' => 'lucinha@alerj.rj.gov.br',
        'Luiz Paulo' => 'luizpaulo@alerj.rj.gov.br',
        'Marcelo Cabeleireiro' => 'marcelocabeleireiro@alerj.rj.gov.br',
        'Marcelo do seu Dino' => 'marcelodoseudino@alerj.rj.gov.br',
        'Márcio Canella' => 'marciocanella@alerj.rj.gov.br',
        'Márcio Gualberto' => 'marciogualberto@alerj.rj.gov.br',
        'Márcio Pacheco' => 'marciopacheco@alerj.rj.gov.br',
        'Marcos Muller' => 'marcosmiller@alerj.rj.gov.br',
        'Marina Rocha' => 'mainarocha@alerj.rj.gov.br',
        'Martha Rocha' => 'martharocha@alerj.rj.gov.br',
        'Max Lemos' => 'maxlemos@alerj.rj.gov.br',
        'Mônica Francisco' => 'monicafrancisco@alerj.rj.gov.br',
        'Brazão' => 'brazao@alerj.rj.gov.br',
        'Renan Ferreirinha' => 'renanferreirinha@alerj.rj.gov.br',
        'Renata Souza' => 'renatasouza@alerj.rj.gov.br',
        'Renato Cozzolino' => 'renatocozzolino@alerj.rj.gov.br',
        'Renato Zaca' => 'renatozaca@alerj.rj.gov.br',
        'Rodrigo Amorim' => 'rodrigoamorim@alerj.rj.gov.br',
        'Rodrigo Bacellar' => 'rodrigobacellar@alerj.rj.gov.br',
        'Rosane Felix' => 'rosanefelix@alerj.rj.gov.br',
        'Rosenverg Reis' => 'rosenvergreis@alerj.rj.gov.br',
        'Samuel Malafaia' => 'samuelmalafaia@alerj.rj.gov.br',
        'Sub Tenente Bernardo' => 'subtenentebernardo@alerj.rj.gov.br',
        'Thiago Pampolha' => 'thiagopampolha@alerj.rj.gov.br',
        'Tia Ju' => 'tiaju@alerj.rj.gov.br',
        'Val Ceasa' => 'valceasa@alerj.rj.gov.br',
        'Valdecy da Saúde' => 'valdecydasaude@alerj.rj.gov.br',
        'Vandro Familia' => 'vandrofamilia@alerj.rj.gov.br',
        'Waldeck Carneiro' => 'waldeckcarneiro@alerj.rj.gov.br',
        'Welberth Rezende' => 'welberthresende@alerj.rj.gov.br',
        'Zeidan Lula' => 'zeidan@alerj.rj.gov.br',
        'Sergio Fernandes' => 'sergiofernandes@alerj.rj.gov.br',
        'Sergio Louback' => 'sergiolouback@alerj.rj.gov.br',
        'Capitão Nelson' => 'capitaonelson@alerj.rj.gov.br',
        'Carlo Caiado' => 'carlocaiado@alerj.rj.gov.br',
    ];

    public function up()
    {
        app(DataSyncService::class)->parties();
        app(DataSyncService::class)->departments();
        app(DataSyncService::class)->congressmen();

        collect(static::USERS)->each(function ($email, $name) {
            User::disableEvents();

            $userCreated = app(User::class)->updateOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'name' => $name,
                    'username' => $email,
                    'password' => 'empty-password',
                ]
            );

            $congressman = app(
                \App\Data\Repositories\Congressmen::class
            )->findByNickname($userCreated->name);

            if (!is_null($congressman)) {
                $userCreated['congressman_id'] = $congressman->id;

                $userCreated['department_id'] = $congressman->department->id;

                $userCreated->save();

                $userCreated->assign(Constants::ROLE_CONGRESSMAN);

                dump('Cadastrando o deputado: ' . $userCreated->username);
            } else {
                dump('Deputado não encontrado: ' . $userCreated->username);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
