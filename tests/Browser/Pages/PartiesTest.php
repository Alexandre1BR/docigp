<?php


namespace Tests\Browser\Pages;


use App\Models\User;
use App\Data\Repositories\Parties;
use App\Support\Constants;
use Faker\Generator as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PartiesTest extends DuskTestCase
{
    private static $nomePartido;
    private static $siglaPartido;
    private static $randomEntryTypes;
    private static $administrator;

    public function createAdminstrator()
    {
        $user = User::factory()->create();
        $user->assign(Constants::ROLE_ADMINISTRATOR);
        static::$administrator = $user;
    }

    public function init()
    {
        $faker = app(Faker::class);
        static::$nomePartido = only_letters_and_space(app(Faker::class)->name);
        static::$siglaPartido = only_letters_and_space(app(Faker::class)->name);
        static::$randomEntryTypes = app(Parties::class)
            ->randomElement()
            ->toArray();
    }

    public function testInsert()
    {
        $this->createAdminstrator();
        $this->init();
        $nomeA = static::$nomePartido;
        $siglaA = static::$siglaPartido;
        $administrator = static::$administrator;


        $this->browse(function (Browser $browser) use ($siglaA, $nomeA, $administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/parties#/')
                ->press('#novo')
                ->type('#code', $siglaA)
                ->type('#name', $nomeA)
                ->press('Gravar')
                ->type('@search-input', $siglaA)
                ->press('@search-button')
                ->assertSee($nomeA)
                ->assertSee($siglaA);
        });
    }

    public function testValidation()
    {

        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/parties#/')
                ->clickLink('Novo')
                ->press('Gravar')
                ->assertSee('O campo código é obrigatório.')
                ->assertSee('O campo nome é obrigatório.');
        });
    }

    public function testAlter()
    {
        $this->init();
        $nomeA = static::$nomePartido;
        $randomEntryTypes1 = static::$randomEntryTypes;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($nomeA, $randomEntryTypes1, $administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/parties/' . $randomEntryTypes1['id'] . '#/')
                ->click('#vue-editButton')
                ->type('#name', '*' . $nomeA . '*')
                ->press('Gravar')
                ->type('@search-input', '*' . $nomeA . '*')
                ->press('@search-button')
                ->assertSee('*' . $nomeA . '*');
        });
    }

    public function testRightSearch()
    {
        $this->init();
        $randomEntryTypes1 = static::$randomEntryTypes;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($randomEntryTypes1,$administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/parties#/')
                ->type('@search-input', $randomEntryTypes1['name'])
                ->click('@search-button')
                ->assertSee($randomEntryTypes1['id']);
        });
    }

    public function testWrongSearch()
    {
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/parties#/')
                ->type('@search-input', '132312312vcxvdsf413543445654')
                ->click('@search-button')
                ->waitForText('Nenhuma Legislatura encontrada')
                ->assertSee('Nenhuma Legislatura encontrada');
        });
    }

}
