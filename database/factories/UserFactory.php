<?php

/** @var Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker, $attrib) {
    static $userCounter = 1;
    static $role = null;
    if ($role !== $attrib['role']) {
        $userCounter = 1;
    }
    $role = $attrib['role'] ?? User::ROLE_STUDENT;
    $userEmail = $role . $userCounter++ . '@email.com';
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $userEmail,
        'email_verified_at' => now(),
        'phone' => $faker->e164PhoneNumber,
        'role' => User::ROLE_STUDENT,
        'avatar_id' => null,
        'password' => '123456',
    ];
});
