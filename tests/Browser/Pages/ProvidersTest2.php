<?php

namespace Tests\Feature;

use App\Data\Repositories\Providers;
use App\Data\Repositories\Users;
use App\Http\Livewire\BaseForm;
use App\Http\Livewire\Providers\Blocked;
use App\Http\Livewire\Providers\CreateForm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire;
use App\Http\Livewire\Providers\Index;
use App\Http\Livewire\Providers\UpdateForm;
use App\Models\Provider;
use App\Models\ProviderBlockPeriod;
use App\Models\User;
use App\Support\Constants;
use Illuminate\Support\Facades\DB;
use Laravel\Dusk\Browser;
use Nette\Utils\Random;
use Tests\DuskTestCase;

class ProvidersTest2 extends DuskTestCase
{   


    public function tests_livewire_indexTable()
    {
        $provider = app(Providers::class)->randomElement()->toArray();

            Livewire::test(Index::class)
            ->assertSee('Fornecedores / Favorecidos')
            ->set('searchString',$provider['name'])
            ->assertSee($provider['cpf_cnpj'])
            ->set('isBlocked',true)
            ->assertSee('Nenhum Fornecedor ou Favorecido encontrado');
    }
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

    public function test_livewire_setBlockedPeriod(){
        Livewire::test(CreateForm::class)
        ->call('store')
        ->assertHasErrors(['start_date' => 'required'])
        ->set('start_date','27/03/1998')
        ->set('end_date','27/03/2050')
        ->assertSet('start_date','27/03/1998')
        ->assertSet('end_date','27/03/2050')
        ->call('store')
        ->assertHasNoErrors('store');
    }
}