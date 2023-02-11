<?php

/** @var Factory $factory */

use App\Models\Solution;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Solution::class, function (Faker $faker) {
    return [
        'completed' => $faker->boolean,
        'teacher_mark' => $faker->optional()->numberBetween(1,12)
    ];
});
