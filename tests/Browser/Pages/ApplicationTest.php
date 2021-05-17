<?php


namespace Tests\Browser\Pages;

use App\Data\Models\Entry;
use App\Data\Models\EntryDocument;
use App\Data\Models\EntryType;
use App\Data\Models\File;
use App\Data\Models\Provider;
use App\Data\Repositories\Congressmen;
use App\Data\Models\User;
use App\Data\Repositories\EntryTypes;
use App\Support\Constants;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;


class ApplicationTest extends DuskTestCase
{
    private static $administrator;
    private static $randomCongressman;
    private static $newEntriesRaw;
    private static $provider;
    private static $randomProvider;
    private static $randomEntryType;
    private static $document;
    private static $randomEntry;
    private static $newEntryDocument;


    public function createAdminstrator()
    {
        static::$administrator = factory(
            User::class,
            Constants::ROLE_ADMINISTRATOR
        )->raw();
    }

    public function init()
    {
        static::$randomCongressman = app(Congressmen::class)
        ->randomElement()
        ->toArray();

        static::$randomEntry = app(EntryTypes::class)
        ->randomElement()
        ->toArray();

        static::$newEntriesRaw = factory(
            Entry::class
        )->raw();

        static::$randomProvider = Provider::find(static::$newEntriesRaw['provider_id']);
        static::$randomEntryType = EntryType::find(static::$newEntriesRaw['entry_type_id']);

        static::$provider = factory(
            Provider::class
        )->raw();

        static::$newEntryDocument = factory(
            File::class
        )->raw();

    }

    public function documentNumber($newEntriesRaw){
        static::$document = $newEntriesRaw['document_number'];
            if (is_null( static::$document)) {
                return "0000";
            } else {
                return $newEntriesRaw['document_number'];
            }
    }


    public function testInsert()
    {
        $this->createAdminstrator();
        $this->init();
        $administrator = static::$administrator;
        $randomCongressman = static::$randomCongressman;
        $newEntriesRaw = static::$newEntriesRaw;
        $document = $this->documentNumber($newEntriesRaw);
        $provider = static::$provider;
        $newEntryDocument = static::$newEntryDocument;
        $rand = rand(1,100);





        $this->browse(function (Browser $inside_user, Browser $outside_user) use (
            $document,
            $administrator,
            $randomCongressman,
            $newEntriesRaw,
            $provider,
            $newEntryDocument,
            $rand

        ) {
            $inside_user
                ->loginAs($administrator['id'])
                ->visit('admin/entries#/')
                ->assertSee('Prestação de Contas')
                ->type('@filter_input',$randomCongressman['name'])
                ->pause(1000)
                ->press('@congressman-'.$randomCongressman['id'])
                ->waitForText('Orçamento mensal')
                ->pause(2000)
                ->waitFor('@percentageButton')
                ->screenshot('Budget')
                ->click('@percentageButton')
                ->type('@input-percentage', $rand)
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitForText('Lançamentos',10)
                ->screenshot('Entry')
                ->click('@newentry')
                ->screenshot('EntryForm')
                ->type('#date', $newEntriesRaw['date']->format('d/m/Y'))
                ->type('@dusk_value',$newEntriesRaw['value'])
                ->click('.vs__selected-options');
            $inside_user
                ->elements('ul.dropdown-menu li a')[1]->click();
            $inside_user
                ->type('#document_number',$document)
                ->type('#object',$newEntriesRaw['object'])
                ->type('#provider_cpf_cnpj',$provider['cpf_cnpj'])
                ->type('#to',$provider['name'])
                ->script('$("div[id=\'cost_center_id\']").children().children()[0].setAttribute(\'class\', \'vs__selected-options1\')');
            $inside_user
                ->click('.vs__selected-options1');
            $inside_user
                ->elements('ul.dropdown-menu li a')[0]->click();
            $inside_user
                ->pause(2000)
                ->screenshot('EntryForm-Filled')
                ->press('Gravar')
                ->pause(3000)
                ->click('@budget')
                ->waitForText('Documentos')
                ->screenshot('Documents')
                ->click('@newEntryDocument')
                ->attach('#dropzone', '/img/logo-alerj-docigp.png') ## nao funciona... pq? ##
                ->screenshot('document_dropped')
                ->press('Gravar')
                ->waitForText('Comentários')
                ->screenshot('Comment')
                ->click('@newEntryComment')
                ->type('#text','teste')
                ->screenshot('testInsert-17')
                ->press('Gravar')
                ->click('@editComment')
                ->type('#text','teste-'.$rand)
                ->press('Gravar')
                ->assertSee('teste-'.$rand)
                ->screenshot('Comment-Edited')
                ->click('@deletComment')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->screeshot('Comment-Deleted')
                ->pause(2000)
                ->press('@verify_document')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->pause(800)
                ->press('@analize_document')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->pause(800)
                ->press('@verify_entry_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@analize_entry_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@close_budget_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@analize_budget_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@publish_budget_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800);
            $outside_user
                ->visit('/transparencia#/')
                ->waitforText('Prestação de Contas')
                ->assertSee($randomCongressman['name'])
                ->screenshot('ApplicationFlow-Success');

        });
    }
}
