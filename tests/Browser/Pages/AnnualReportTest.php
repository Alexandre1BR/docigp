<?php


namespace Tests\Browser\Pages;


use App\Data\Models\User;
use App\Support\Constants;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AnnualReportTest extends DuskTestCase
{

    private static $administrator;

    public function createAdminstrator()
    {
        static::$administrator = factory(
            User::class,
            Constants::ROLE_ADMINISTRATOR
        )->raw();
    }

    public function testInsert()
    {
        $this->createAdminstrator();
        $administrator = static::$administrator;
        $year = [2019,2020,2021];
        $random_date = $year[array_rand($year,1)];

        $this->browse(function (Browser $browser) use (
            $administrator,
            $random_date

        ) {
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/annual-report#/')
                ->assertSee('Relatórios anuais')
                ->type('@CongressmanReport', $random_date)
                ->select('congressman_id',1)
                ->press('@submit-CongressmanReport');
            $browser
                ->loginAs($administrator['id'])
                ->visit('admin/annual-report#/')
                ->assertSee('Relatórios anuais')
                ->type('@Report', $random_date)
                ->press('@submit-Report');
        });
    }
}

