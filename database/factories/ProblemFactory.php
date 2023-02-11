<?php

/** @var Factory $factory */

use App\Models\Problem;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Problem::class, function (Faker $faker) {
    return [
        'title' => $faker->word . ' Problem'
    ];
});
