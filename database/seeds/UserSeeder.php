<?php

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create(['role' => User::ROLE_ADMIN]);
        factory(User::class, 5)->create(['role' => User::ROLE_TEACHER]);
        factory(User::class, 20)->create(['role' => User::ROLE_STUDENT]);
        factory(User::class, 5)->create(['role' => User::ROLE_PARENT]);

        User::all()->each(function (User $user) {
            /** @var Attachment $avatar */
            $avatar = factory(Attachment::class)->state('avatar')->create(['user_id' => $user->id]);
            $avatar->avatarUser()->save($user);
        });
    }
}
