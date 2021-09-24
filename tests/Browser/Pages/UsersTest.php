<?php


namespace Tests\Browser\Pages;


use App\Models\User;
use App\Data\Repositories\Users;
use App\Support\Constants;
use Faker\Generator as Faker;
use Laravel\Dusk\Browser;
use Silber\Bouncer\Database\Role as BouncerRole;
use Tests\DuskTestCase;

class UsersTest extends DuskTestCase
{

    private static $usersRaw;
    private static $randomUsers;
    private static $administrator;

    public function createAdminstrator()
    {
        $user = User::factory()->create();
        $user->assign(Constants::ROLE_ADMINISTRATOR);
        static::$administrator = $user;
    }

    public function init()
    {
        static::$usersRaw = User::factory()->make();
        static::$randomUsers = app(Users::class)
            ->randomElement()
            ->toArray();
    }

    public function testInsert()
    {
        $this->createAdminstrator();
        $this->init();
        $user = static::$usersRaw;
        $allRoles = BouncerRole::all();
        $selectedRole = app(Faker::class)->randomElement($allRoles);
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use (
            $selectedRole,
            $user,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/users#/')
                ->assertSee('Novo')
                ->press('#novo')
                ->type('#email', $user['email'])
                ->type('#name', $user['name'])
                ->select('#all-roles', $selectedRole['id'])
                ->press('#add-profile-button')
                ->press('Gravar')
                ->assertSee($user['name'])
                ->assertSee($selectedRole['id'])
                ->assertSee($user['email']);
        });
        $this->assertDatabaseHas('users', [
            'name' => $user['name'],
            'email' => $user['email'],
        ]);
    }
    public function testValidation()
    {
        $administrator = static::$administrator;
        $allRoles = BouncerRole::all();
        $selectedRole = app(Faker::class)->randomElement($allRoles);

        $this->browse(function (Browser $browser) use (
            $selectedRole,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/users#/')
                ->assertSee('Novo')
                ->press('#novo')
                ->select('#all-roles', $selectedRole['id'])
                ->press('#add-profile-button')
                ->waitFor('#assigned-roles', $selectedRole['id'])
                ->press('Gravar')
                ->assertSee('O campo e-mail é obrigatório.')
                ->assertSee('O campo nome é obrigatório.');
        });
    }
    public function testAlterUser()
    {
        $this->createAdminstrator();
        $this->init();
        $user = static::$usersRaw;
        $randomUsers1 = static::$randomUsers;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use (
            $user,
            $randomUsers1,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/users/show/' . $randomUsers1['id'] . '#/')
                ->click('#vue-editButton')
                ->type('#name', '*' . $user['name'] . '*')
                ->press('Gravar')
                ->assertSee('*' . $user['name'] . '*');
        });
    }
    public function testWrongSearch()
    {
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use ($administrator) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/users#/')
                ->type('@search-input', '1323e12312vcxvdsf413543445654')
                ->click('@search-button')
                ->waitForText('Nenhum Usuário encontrado.')
                ->assertSee('Nenhum Usuário encontrado.');
        });
    }

    public function testRightSearch()
    {
        $this->init();
        $randomUsers1 = static::$randomUsers;
        $administrator = static::$administrator;

        $this->browse(function (Browser $browser) use (
            $randomUsers1,
            $administrator
        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/users#/')
                ->type('@search-input', $randomUsers1['name'])
                ->click('@search-button')
                ->assertSee($randomUsers1['name']);
        });
    }
}

