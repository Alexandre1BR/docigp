<?php
namespace Tests\Browser\Pages;

use App\Data\Models\User;
use App\Data\Repositories\Legislatures;
use App\Support\Constants;
use Faker\Generator as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LegislaturesTest extends DuskTestCase
{
    private static $anoLegislatura;
    private static $numeroLegislatura;
    private static $randomLegislatures;
    private static $administrator;

    public function createAdminstrator()
    {
        static::$administrator = factory(
            User::class,
            Constants::ROLE_ADMINISTRATOR
        )->raw();
    }

    public function init()
    {
        static::$anoLegislatura = only_numbers(app(Faker::class)->year);
        static::$numeroLegislatura = only_numbers(
            app(Faker::class)->numberBetween(0, 1000)
        );
        static::$randomLegislatures = app(Legislatures::class)
            ->randomElement()
            ->toArray();
    }

    public function testInsert()
    {
        $this->createAdminstrator();
        $this->init();
        $ano = static::$anoLegislatura;
        $ano1 = static::$anoLegislatura + 1;
        $numero = static::$numeroLegislatura;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use (
            $ano,
            $ano1,
            $numero,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/legislatures#/')
                ->assertSee('Novo')
                ->press('#novo')
                ->type('#number', $numero)
                ->type('#year_start', $ano)
                ->type('#year_end', $ano1)
                ->press('Gravar')
                ->assertSee($ano, $ano1, $numero);
        });
    }

    public function testValidation()
    {
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/legislatures/create#/')
                ->screenshot('ERRO')
                ->press('#submitButton')
                ->pause(1000)
                ->screenshot('ERRO-2')
                ->assertSee('O campo número é obrigatório.')
                ->assertSee('O campo ano de início é obrigatório.')
                ->assertSee('O campo ano final é obrigatório.');
        });
    }

    public function testAlter()
    {
        $this->init();
        $ano = static::$anoLegislatura;
        $numero = static::$numeroLegislatura;
        $ano1 = static::$anoLegislatura + 1;
        $randomLegislatures1 = static::$randomLegislatures;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use (
            $ano,
            $ano1,
            $numero,
            $randomLegislatures1,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit(
                    'admin/legislatures/' . $randomLegislatures1['id'] . '#/'
                )
                ->click('#vue-editButton')
                ->type('#number', $numero . 00)
                ->type('#year_start', $ano)
                ->type('#year_end', $ano1)
                ->press('Gravar')
                ->assertSee($numero . 00)
                ->assertSee($ano)
                ->assertSee($ano1);
        });
    }

    public function testWrongSearch()
    {
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/legislatures#/')
                ->type('@search-input', '132312312vcxvdsf413543445654')
                ->click('@search-button')
                ->waitForText('Nenhuma Legislatura encontrada')
                ->screenshot('Legislatures-wrongsearch')
                ->assertSee('Nenhuma Legislatura encontrada');
        });
    }

    public function testRightSearch()
    {
        $this->init();
        $randomLegislatures1 = static::$randomLegislatures;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use (
            $randomLegislatures1,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/legislatures#/')
                ->type('@search-input', $randomLegislatures1['number'])
                ->click('@search-button')
                ->assertSee($randomLegislatures1['year_start'])
                ->screenshot('Legislatures-rightsearch');
        });
    }
}
