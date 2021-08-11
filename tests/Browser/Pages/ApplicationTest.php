<?php

namespace Tests\Browser\Pages;

use App\Models\Entry;
use App\Models\EntryDocument;
use App\Models\EntryType;
use App\Models\File;
use App\Models\Provider;
use App\Data\Repositories\Congressmen;
use App\Models\User;
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
        $user = User::factory()->create();
        $user->assign(Constants::ROLE_ADMINISTRATOR);
        static::$administrator = $user;
    }

    public function init()
    {
        static::$randomCongressman = app(Congressmen::class)
            ->randomElement()
            ->toArray();

        static::$randomEntry = app(EntryTypes::class)
            ->randomElement()
            ->toArray();

        static::$newEntriesRaw = Entry::factory()->make();

        static::$randomProvider = Provider::find(static::$newEntriesRaw['provider_id']);
        static::$randomEntryType = EntryType::find(static::$newEntriesRaw['entry_type_id']);

        static::$provider = Provider::factory()->make();

        //        static::$newEntryDocument = EntryDocument::factory()->make();
    }

    public function documentNumber($newEntriesRaw)
    {
        static::$document = $newEntriesRaw['document_number'];
        if (is_null(static::$document)) {
            return '0000';
        } else {
            return $newEntriesRaw['document_number'];
        }
    }

    /**
     * @test
     * @group testInsert
     * @group link
     */

    public function testInsertEntry()
    {
        $this->createAdminstrator();
        $this->init();
        $administrator = static::$administrator;
        $randomCongressman = static::$randomCongressman;
        $newEntriesRaw = static::$newEntriesRaw;
        $document = $this->documentNumber($newEntriesRaw);
        $provider = static::$provider;
        $rand = rand(1, 100);

        $this->browse(function (Browser $inside_user, Browser $outside_user) use (
            $document,
            $administrator,
            $randomCongressman,
            $newEntriesRaw,
            $provider,
            $rand
        ) {
            $inside_user
                ->loginAs($administrator['id'])
                ->visit('admin/entries#/')
                ->assertSee('Prestação de Contas')
                ->type('@filter_input', $randomCongressman['name'])
                ->pause(3000)
                ->press('@congressman-' . $randomCongressman['id'])
                ->waitForText('Orçamento mensal')
                ->pause(4000)
                //                ->waitFor('@percentageButton')
                // ->screenshot('1-Budget')
                ->script('$("tr[class=\'cursor-pointer\']").click()');
            //                ->pause(2000)
            //                ->script('document.querySelector("button[dusk=\'percentageButton\']").click();');
            //            $inside_user
            //                ->type('@input-percentage', $rand)
            //                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitForText('Lançamentos', 10)
                // ->screenshot('2-Entry')
                ->click('@newentry')
                ->pause('1000')
                // ->screenshot('3-EntryForm')
                ->type('#date', $newEntriesRaw['date']->format('d/m/Y'))
                ->type('@dusk_value', $newEntriesRaw['value'])
                ->click('.vs__selected-options');
            $inside_user->elements('.vs__dropdown-option')[0]->keydown();
            $inside_user
                ->type('#document_number', $document)
                ->type('#object', $newEntriesRaw['object'])
                ->type('#provider_cpf_cnpj', $provider['cpf_cnpj'])
                ->type('#to', $provider['name'])
                ->script(
                    '$("div[id=\'cost_center_id\']").children().children()[0].setAttribute(\'class\', \'vs__selected-options1\')'
                );
            $inside_user->click('.vs__selected-options1');
            $inside_user->elements('.vs__dropdown-option')[0]->click();
            $inside_user
                ->pause(2000)
                // ->screenshot('4-EntryForm-Filled')
                ->press('@record')
                ->pause('1000')
                ->script('location.reload()');
            $inside_user
                ->assertSee('Prestação de Contas')
                ->type('@filter_input', $randomCongressman['name'])
                ->pause(3000)
                ->press('@congressman-' . $randomCongressman['id'])
                ->waitForText('Orçamento mensal')
                ->pause(4000)
                ->script('$("tr[class=\'cursor-pointer\']").click()');
            $inside_user
                ->pause(6000)
                ->script('$("tr[dusk=\'entrie\']").click()');
            //                ->waitFor('@entrie',8)
            //                ->click('@entrie')
            $inside_user
                ->waitForText('Documentos')
                ->click('@newEntryDocument')
                ->waitForText('Novo documento')
                ->attach('input.dz-hidden-input', 'public/img/logo-alerj-docigp.png')
                ->pause(5000)
                // ->screenshot('5-Document_dropped')
                ->press('Fechar')
                ->script('window.scrollTo(0,document.body.scrollHeight);');
            $inside_user
                // ->screenshot('6-Comment')
                ->script('$("button[dusk=\'newEntryComment\']").click()');
            $inside_user->type('#text', 'teste')->press('Gravar');
            $inside_user
                ->script('location.reload()');
            $inside_user
            ->assertSee('Prestação de Contas')
                ->type('@filter_input', $randomCongressman['name'])
                ->pause(3000)
                ->press('@congressman-' . $randomCongressman['id'])
                ->waitForText('Orçamento mensal')
                ->pause(4000)
                ->script('$("tr[class=\'cursor-pointer\']").click()');
            $inside_user
                ->pause(6000)
                ->script('$("tr[dusk=\'entrie\']").click()');
            $inside_user
                ->script('window.scrollTo(0,document.body.scrollHeight);');
            $inside_user
                ->waitFor('@editComment')
                ->script('$("button[dusk=\'editComment\']").click()');
            $inside_user->type('#text', $rand)->press('Gravar');
            $inside_user
                ->script('location.reload()');
            $inside_user
            ->assertSee('Prestação de Contas')
                ->type('@filter_input', $randomCongressman['name'])
                ->pause(3000)
                ->press('@congressman-' . $randomCongressman['id'])
                ->waitForText('Orçamento mensal')
                ->pause(4000)
                ->script('$("tr[class=\'cursor-pointer\']").click()');
            $inside_user
                ->pause(6000)
                ->script('$("tr[dusk=\'entrie\']").click()');
            $inside_user
                ->script('window.scrollTo(0,document.body.scrollHeight);');
            $inside_user
                ->pause(1000)
                ->waitForText($rand)
                ->assertSee($rand)
                // ->screenshot('7-Comment-Edited')
                ->script('window.scrollTo(0 , document.body.scrollHeight);');
            $inside_user->script('$("button[dusk=\'deleteComment\']").click()');
            $inside_user->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                // ->screenshot('8-Comment-Deleted')
                ->pause(2000)
                ->script('$("button[dusk=\'verify_document\']").click()');
            $inside_user
                // ->screenshot('9-verify_document')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user->pause(2000)->script('$("button[dusk=\'analize_document\']").click()');
            $inside_user
                // ->screenshot('10-analize_document')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user->pause(2000)->script('$("button[dusk=\'verify_entry_button\']").click()');
            $inside_user
                // ->screenshot('11-verify_entry_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(2000)
                ->script('$("button[dusk=\'analize_entry_button\']").click()');
            $inside_user
                // ->screenshot('12-analize_entry_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(3500)
                ->script('$("button[dusk=\'close_budget_button\']").click()');
            $inside_user
                // ->screenshot('13-close_budget_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(2500)
                ->script('$("button[dusk=\'analize_budget_button\']").click()');
            $inside_user
                // ->screenshot('14-analize_budget_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user
                ->waitUntilMissing('Salvo com sucesso')
                ->pause(2500)
                ->script('$("button[dusk=\'publish_budget_button\']").click()');
            $inside_user
                // ->screenshot('15-publish_budget_button')
                ->script('$("button[class=\'swal2-confirm swal2-styled\']").click()');
            $inside_user->waitUntilMissing('Salvo com sucesso')->pause(2000);
            $outside_user
                ->logout()
                ->visit('/transparencia#/')
                ->waitforText('Prestação de Contas')
                ->type('@filter_input', $randomCongressman['name'])
                ->waitFor('@congressman-' . $randomCongressman['id'])
                ->assertSee($randomCongressman['nickname'])
                ->waitForText('Aderiu?')
                ->assertSee('sim');
                // ->screenshot('16-ApplicationFlow-Success');
        });
    }
}
