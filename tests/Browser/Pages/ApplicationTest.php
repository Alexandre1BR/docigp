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
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;



class AplicationTest extends DuskTestCase
{
    private static $administrator;
    private static $randomCongressman;
    private static $newentriesRaw;
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

        static::$newentriesRaw = factory(
            Entry::class
        )->raw();

        static::$randomProvider = Provider::find(static::$newentriesRaw['provider_id']);
        static::$randomEntryType = EntryType::find(static::$newentriesRaw['entry_type_id']);

        static::$provider = factory(
            Provider::class
        )->raw();

        static::$newEntryDocument = factory(
            File::class
        )->raw();

    }

    public function documentNumber($newentriesRaw){
        static::$document = $newentriesRaw['document_number'];
            if (is_null( static::$document)) {
                return "0000";
            } else {
                return $newentriesRaw['document_number'];
            }
    }

    public function id_to_name($case,$newentriesRaw){
        $entry_type_id = $newentriesRaw['entry_type_id'];
        $cost_type_id = $newentriesRaw['cost_center_id'];
        if ($case = 'entry_type_id'){
            $entry_type_name = DB::table('entry_types')->where('id','=', $entry_type_id )->first();
            return $entry_type_name->name;
        } else {
            $cost_type_name = DB::table('cost_centers')->where('id','=', $cost_type_id )->first();
            return $cost_type_name->name;
        }
    }

    public function testInsert()
    {
        $this->createAdminstrator();
        $this->init();
        $administrator = static::$administrator;
        $randomCongressman = static::$randomCongressman;
        $newentriesRaw = static::$newentriesRaw;
        $document = $this->documentNumber($newentriesRaw);
        $provider = static::$provider;
        $randomProvider1 = static::$randomProvider;
        $randomEntryType1 = static::$randomEntryType;
        $randomEntry = static::$randomEntry;
        $newEntryDocument = static::$newEntryDocument;
        $rand = rand(1,100);
        $entry_type_name = $this->id_to_name('entry_type_id',$newentriesRaw);
        $cost_center_name = $this->id_to_name('cost_type_id',$newentriesRaw);




        $this->browse(function (Browser $inside_user, Browser $outside_user) use (
            $document,
            $administrator,
            $randomCongressman,
            $newentriesRaw,
            $provider,
            $randomProvider1,
            $randomEntryType1,
            $randomEntry,
            $newEntryDocument,
            $rand,
            $entry_type_name,
            $cost_center_name

        ) {
            $inside_user
                ->loginAs($administrator['id'])
                ->visit('admin/entries#/')
                ->assertSee('Prestação de Contas')
                ->select('@custom-select', 250)
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
                ->waitForText('Salvo com sucesso',10)
                ->waitForText('Lançamentos')
                ->screenshot('Entry')
                ->click('@newentry')
                ->screenshot('EntryForm')
                ->type('#date', $newentriesRaw['date'])
                ->type('@dusk-value',$newentriesRaw['value'])
                ->click('.vs__selected-options');
            $inside_user
                ->elements('ul.dropdown-menu li a')[0]->click();
            $inside_user
                ->type('#document_number',$document)
                ->type('#object',$newentriesRaw['object'])
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
                ->pause(6000)
                ->click('@budget')
                ->waitForText('Documentos')
                ->screenshot('Documents')
                ->click('@newEntryDocument')
                ->drop($newEntryDocument)
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
                ->press('Confirmar')
                ->screeshot('Comment-Deleted')
                ->pause(2000)
                ->press('@verify_document')
                ->press('confirmar')
                ->pause(800)
                ->press('@analize_document')
                ->press('confirmar')
                ->pause(800)
                ->press('@verify_entry_button')
                ->press('confirmar')
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@analize_entry_button')
                ->press('confirmar')
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@close_budget_button')
                ->press('confirmar')
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@analize_budget_button')
                ->press('confirmar')
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800)
                ->press('@publish_budget_button')
                ->press('confirmar')
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(800);
            $outside_user
                ->visit('/transparencia#/')
                -waitforText('Prestação de Contas')
                ->assertSee($randomCongressman['name'])
                ->screenshot('ApplicationFlow-Success');

        });
    }
}
#teste

