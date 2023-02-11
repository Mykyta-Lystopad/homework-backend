<?php

/** @var Factory $factory */

use App\Models\Group;
use App\Models\Subject;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Group::class, function (Faker $faker) {
    return [
        'title' => $faker->bothify('##?'),
        'note' => $faker->word,
        'subject_id' => Subject::inRandomOrder()->first()->id
    ];
});
