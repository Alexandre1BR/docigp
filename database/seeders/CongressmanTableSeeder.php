<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Constants;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Congressman;
use App\Models\CongressmanLegislature;

class CongressmanTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $department = factory(Department::class)->create([
            'name' => ($name = 'Manuel Francisco dos Santos'),
        ]);

        $congressman = factory(Congressman::class)->create([
            'name' => $name,
            'department_id' => $department->id,
        ]);

        factory(CongressmanLegislature::class)->create([
            'congressman_id' => $congressman->id,
        ]);

        $user = factory(User::class)->create([
            'name' => $name,
            'email' => 'docigp@alerj.rj.gov.br',
            'congressman_id' => $congressman->id,
            'department_id' => $department->id,
        ]);

        $user->assign(Constants::ROLE_CONGRESSMAN);
    }
}
