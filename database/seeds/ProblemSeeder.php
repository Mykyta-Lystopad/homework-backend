<?php

use App\Models\Assignment;
use App\Models\Attachment;
use App\Models\Problem;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Assignment::all()->each(function (Assignment $assignment) {
            $assignment->problems()->saveMany(factory(Problem::class, 3)->make(['user_id'=> $assignment->user_id]));
            $assignment->attachments()->saveMany(factory(Attachment::class, 2)->state('image')->make(['user_id'=> $assignment->user_id]));
        });
    }
}
