<?php

use App\Models\CostCenter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostCentersTable extends Migration
{
    private function populate()
    {
        $data = [
            '1' => [
                'name' => 'Crédito em conta-corrente',
                'limit' => 0,
                'frequency' => 'monthly',
                'can_accumulate' => true,
            ],

            '2' => [
                'name' => 'Transporte de saldo acumulado para o próximo período',
                'limit' => 0,
                'frequency' => 'monthly',
                'can_accumulate' => true,
            ],

            '3' => [
                'name' => 'Transporte de saldo acumulado do período anterior',
                'limit' => 0,
                'frequency' => 'monthly',
                'can_accumulate' => true,
            ],

            'I' => [
                'name' => 'Passagens aéreas e terrestres',
                'limit' => 0,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],

            'II' => [
                'name' =>
                    'Serviços e produtos postais previstos nos contratos celebrados pela Assembleia Legislativa, vedadas as aquisições de selos e remessas de cartões postais, até o limite mensal inacumulável de 5% do valor da DOCIGP',
                'limit' => 5,
                'type' => 'monthly',
                'can_accumulate' => false,
            ],

            'III' => [
                'name' =>
                    'Manutenção do Gabinete Parlamentar e de Escritórios de Apoio à Atividade Parlamentar, até o limite mensal inacumulável de 20% do valor da DOCIGP',
                'limit' => 20,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],

            'III.a' => ['name' => 'Locação de imóveis'],
            'III.b' => ['name' => 'Condomínio'],
            'III.c' => ['name' => 'IPTU e seguro contra incêndio'],
            'III.d' => [
                'name' => 'Serviços de energia elétrica, água e esgoto',
            ],

            'IV' => [
                'name' =>
                    'Custeio de despesas vinculadas ao Gabinete Parlamentar e aos Escritórios de Apoio à Atividade Parlamentar previstos no inciso III supra, até o limite mensal inacumulável de 10% do valor da DOCIGP',
                'limit' => 10,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],
            'IV.a' => ['name' => 'Locação de móveis e equipamentos'],
            'IV.b' => [
                'name' => 'Material de expediente e suprimentos de informática',
            ],
            'IV.c' => ['name' => 'Acesso à internet'],
            'IV.d' => ['name' => 'Assinatura de TV a cabo ou similar'],
            'IV.e' => [
                'name' => 'Locação ou aquisição de licença de uso de software',
            ],
            'IV.f' => [
                'name' =>
                    'Serviço de segurança patrimonial, presencial ou remoto',
            ],
            'IV.g' => ['name' => 'Assinatura de publicações'],

            'V' => [
                'name' => 'Fornecimento de alimentação do Parlamentar',
                'limit' => 0.1,
                'frequency' => 'daily',
                'can_accumulate' => false,
            ],

            'VI' => ['name' => 'Outras despesas com locomoção'],
            'VI.a' => [
                'name' => 'Locação ou fretamento de veículos automotores',
            ],
            'VI.b' => [
                'name' =>
                    'Serviços de táxi, serviços de transporte individual privado de passageiros baseado em tecnologia de comunicação em rede - STIP',
                'limit' => 10,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],

            'VII' => [
                'name' => 'Combustíveis e lubrificantes',
                'limit' => 20,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],

            'VIII' => [
                'name' =>
                    'Divulgação da sua atividade parlamentar, exceto nos cento e vinte dias anteriores à data das eleições de âmbito federal, estadual ou municipal',
                'limit' => 30,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],

            'IX' => [
                'name' =>
                    'Participação do Parlamentar em cursos, palestras, seminários, simpósios, congressos ou eventos congêneres, realizados por instituição especializada',
                'limit' => 15,
                'frequency' => 'monthly',
                'can_accumulate' => false,
            ],
        ];

        foreach ($data as $code => $item) {
            $parent = preg_replace('/(.*)\.(.*)/', '$1', $code);

            CostCenter::create([
                'code' => $code,
                'parent_code' => $code == $parent ? null : $parent,
                'name' => $item['name'],
                'limit' => $item['limit'] ?? null,
                'frequency' => $item['frequency'] ?? null,
                'can_accumulate' => $item['can_accumulate'] ?? null,
            ]);
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_centers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->index();

            $table
                ->string('parent_code')
                ->nullable()
                ->index();

            $table->string('name');

            $table->string('frequency')->nullable();

            $table->decimal('limit', 20, 2)->nullable();

            $table->boolean('can_accumulate')->nullable();

            $table
                ->bigInteger('created_by_id')
                ->unsigned()
                ->nullable();

            $table
                ->bigInteger('updated_by_id')
                ->unsigned()
                ->nullable();

            $table->timestamps();
        });

        $this->populate();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_centers');
    }
}
