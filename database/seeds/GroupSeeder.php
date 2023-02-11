<?php

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereRole(User::ROLE_TEACHER)->get()->each(function (User $user) {
            $user->myGroups()->saveMany(factory(Group::class, 2)->make());
        });

        $groups = Group::all();
        $students = User::whereRole(User::ROLE_STUDENT)->get();

        $students->each(function (User $user) use ($groups) {
            $user->userGroups()->saveMany($groups);
        });

        User::whereRole(User::ROLE_PARENT)->get()->each(function (User $user) use ($students) {
            $user->children()->saveMany($students);
        });
    }
}
