<?php

/** @var Factory $factory */

use App\Models\Subject;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Subject::class, function (Faker $faker) {
    return [
        'title' => $faker->word . ' Subject',
        'user_id' => 1
    ];
});
