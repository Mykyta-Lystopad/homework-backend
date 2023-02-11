<?php

/** @var Factory $factory */

use App\Models\Message;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'message' => $faker->sentence
    ];
});
