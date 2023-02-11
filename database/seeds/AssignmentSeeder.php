<?php

use App\Models\Assignment;
use App\Models\Group;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::all()->each(function (Group $group) {
            $assignments = factory(Assignment::class, 2)->make(['user_id' => $group->user_id]);
            $group->assignments()->saveMany($assignments);
            $assignments->each(function (Assignment $assignment) use ($group) {
                $assignment->users()->attach($group->users);
            });
        });
    }
}
