<?php

namespace Database\Factories;


use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

//$factory->define(Department::class, function () {
//    return [
//        'name' => faker()->name,
//        'initials' => faker()->name,
//    ];
//});


class DepartmentFactory extends Factory{

    protected $model = Department::class;

    public function definition()
    {
        return [
            'name' => faker()->name,
            'initials' => faker()->name,
        ];
    }
}
