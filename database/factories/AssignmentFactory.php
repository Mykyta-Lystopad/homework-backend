<?php

/** @var Factory $factory */

use App\Models\Assignment;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Assignment::class, function (Faker $faker) {
    return [
        'title' => $faker->word . ' Assignment',
        'description' => $faker->sentence
    ];
});
