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
        $department = Department::factory()->create([
            'name' => ($name = 'Manuel Francisco dos Santos'),
        ]);

        $congressman = Congressman::factory()->create([
            'name' => $name,
            'department_id' => $department->id,
        ]);

        CongressmanLegislature::factory()->create([
            'congressman_id' => $congressman->id,
        ]);

        $user = User::factory()->create([
            'name' => $name,
            'email' => 'docigp@alerj.rj.gov.br',
            'congressman_id' => $congressman->id,
            'department_id' => $department->id,
        ]);

        $user->assign(Constants::ROLE_CONGRESSMAN);
    }
}
