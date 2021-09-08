<?php

namespace Tests\Feature;

use App\Data\Repositories\Providers;
use App\Http\Livewire\Providers\CreateForm;
use App\Http\Livewire\Providers\Index;
use App\Models\User;
use App\Support\Constants;
use Livewire\Livewire;
use Tests\DuskTestCase;

class ProvidersTest extends DuskTestCase
{   
     /**
     * @test
     * @group tests_livewire_indexTable
     * @group link
     */

    //Livewire - Tabela principal
    public function tests_livewire_indexTable()
    {
        $provider = app(Providers::class)->randomElement()->toArray();

           Livewire::test(Index::class)
            ->assertSee('Fornecedores / Favorecidos')
            ->set('searchString',$provider['name'])
            ->assertSet('searchString',$provider['name'])
            ->assertSee($provider['cpf_cnpj'])
            ->set('isBlocked',true)
            ->assertSee('Nenhum Fornecedor ou Favorecido encontrado');
    }

     /**
     * @test
     * @group tests_alert
     * @group link
     */

    //Validação ao criar novo provider
    public function tests_alerts()
    {
        $user = User::factory()->create();
        $user->assign(Constants::ROLE_ADMINISTRATOR);
    
        $this->browse(function ($browser) use ($user) {
          $browser
            ->loginAs($user->id)
            ->visit('admin/providers/create#/')
            ->click('#submitButton')
            ->assertSee('O CNPJ não é válido.')
            ->assertSee('O campo Tipo pessoa (PJ ou PF) é obrigatório.')
            ->assertSee('O campo nome é obrigatório.');
        });
    }

     /**
     * @test
     * @group tests_newProvider_and_alter
     * @group link
     */

    //Criação de um novo Fornecedor e Alterção 
    public function tests_newProvider_and_alter()
    {
        $user = User::factory()->create();
        $user->assign(Constants::ROLE_ADMINISTRATOR);
        $cpf_or_cnpj =  collect([['87979487052','PF'],['31901926000181','PJ']]);
        $random = $cpf_or_cnpj->random();
        $provider_name = faker()->name;
        $provider =  app(Providers::class)->randomElement()->toArray();
    
        $this->browse(function ($browser) use ($user,$random,$provider_name,$provider) {
          $browser
            ->loginAs($user->id)
            ->visit('admin/providers/create#/')
            ->click('#submitButton')
            ->assertSee('O CNPJ não é válido.')
            ->assertSee('O campo Tipo pessoa (PJ ou PF) é obrigatório.')
            ->assertSee('O campo nome é obrigatório.')
            ->visit('admin/providers/create#/')
            ->typeSlowly('#cpf_cnpj',$random[0])
            ->select('#type',$random[1])
            ->type('#name',$provider_name)
            ->typeSlowly('#zipcode','20020100')
            ->type('#street','Avenida Nilo Peçanha')
            ->type('#number','175')
            ->type('#neighborhood','Centro')
            ->type('#city','Rio de Janeiro')
            ->type('#state','RJ')
            ->click('#submitButton')
            ->assertSee('Fornecedores / Favorecidos');
          $browser
            ->visit('admin/providers/'.$provider['id'].'#/')
            ->press('Alterar')
            ->type('#name', '##'.$provider['name'].'%%')
            ->click('#submitButton');
            $this->assertDatabaseHas('providers', [
                'id' => $provider['id'],
                'name' => '##'.$provider['name'].'%%',
            ]);
        });
    }

     /**
     * @test
     * @group test_livewire_setBlockedPeriod
     * @group link
     */

    //Livewire - Períodos de Bloqueio
    public function test_livewire_setBlockedPeriod(){
        Livewire::test(CreateForm::class)
        ->call('store')
        ->assertHasErrors(['start_date' => 'required'])
        ->set('start_date','28/04/1990')
        ->set('end_date','28/04/2050')
        ->assertSet('start_date','28/04/1990')
        ->assertSet('end_date','28/04/2050')
        ->call('store')
        ->assertHasNoErrors('store');
    }

     /**
     * @test
     * @group test_setBlockedPeriod
     * @group link
     */
    
    //Períodos de Bloqueio e validacao do checkbox da tabela principal
    public function test_setBlockedPeriod(){
        
        $provider =  app(Providers::class)->randomElement()->toArray();
        $today = now();
        $tomorrow = $today->day +1;
    
        $this->browse(function ($browser) use ($provider,$today,$tomorrow) {
            $browser
                ->loginAs($provider['id'])
                ->visit('admin/providers/'.$provider['id'].'#/')
                ->press('@period')
                ->waitForText('Criar período',6)
                ->append('#start_date',$today->day.$today->month.$today->year)
                ->append('#end_date',$today->day.$tomorrow.$today->year)
                ->press('@salvar')
                ->pause(1000)
                ->assertSee($today->format('d/m/Y'))
                ->visit('admin/providers/')
                ->check('@checkbox_block')
                ->assertSee($provider['name']);
        });
    }
}
