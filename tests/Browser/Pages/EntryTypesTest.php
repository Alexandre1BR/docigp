<?php


namespace Tests\Browser\Pages;

use App\Models\User;
use App\Data\Repositories\EntryTypes;
use Faker\Generator as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Support\Constants;

class EntryTypesTest extends DuskTestCase
{
    private static $nomeTipoLancamento;
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
        static::$nomeTipoLancamento = only_letters_and_space(app(Faker::class)->name);
        static::$randomEntryTypes =  app(EntryTypes::class)
            ->randomElement()
            ->toArray();
    }


    public function testInsert()
    {
        $this->createAdminstrator();
        $this->init();
        $nomeA = static::$nomeTipoLancamento;
        $administrator = static::$administrator;


        $this->browse(function (Browser $browser) use ($nomeA,$administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/entry-types#/')
                ->assertSee('Novo')
                ->press('#novo')
                ->type('#name', $nomeA )
                ->press('Gravar')
                ->assertSee( $nomeA);
        });
    }

    public function testValidation()
    {

        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/entry-types#/')
                ->clickLink('Novo')
                ->press('Gravar')
                ->assertSee('O campo nome é obrigatório.');
        });
    }

    public function testDocumentRequire()
    {
        $this->init();
        $nomeA = static::$nomeTipoLancamento;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($nomeA,$administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/entry-types#/')
                ->clickLink('Novo')
                ->type('#name',$nomeA)
                ->press('#document_number_required')
                ->press('Gravar')
                ->assertSee($nomeA, 'Sim');

        });
    }

    public function testAlter()
    {
        $this->init();
        $nomeA = static::$nomeTipoLancamento;
        $randomEntryTypes1 = static::$randomEntryTypes;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($nomeA,$randomEntryTypes1,$administrator){
            $browser->loginAs($administrator['id'])
                ->visit('admin/entry-types/' . $randomEntryTypes1['id'] . '#/')
                ->click('#vue-editButton')
                ->type('#name', '*' . $nomeA . '*')
                ->press('Gravar')
                ->assertSee('*' . $nomeA . '*');

        });
    }

    public function testWrongSearch()
    {
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/entry-types#/')
                ->type('@search-input', '132312312vcxvdsf413543445654')
                ->click('@search-button')
                ->waitForText('Nenhum Tipo de Lançamento encontrado')
                ->screenshot('EntryTypes-wrongsearch')
                ->assertSee('Nenhum Tipo de Lançamento encontrado');
        });
    }

    public function testRightSearch()
    {
        $this->init();
        $randomEntryTypes1 = static::$randomEntryTypes;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($randomEntryTypes1,$administrator) {
            $browser->loginAs($administrator['id'])
                ->visit('admin/entry-types#/')
                ->type('@search-input',$randomEntryTypes1['name'])
                ->click('@search-button')
                ->assertSee($randomEntryTypes1['name'])
                ->screenshot('EntryTypes-rightsearch');
        });
    }

}
