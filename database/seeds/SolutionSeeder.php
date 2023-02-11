<?php

use App\Models\Answer;
use App\Models\Attachment;
use App\Models\Problem;
use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Answer::all()->each(function (Answer $answer) {
            $answer->attachments()->saveMany(factory(Attachment::class, 2)->state('image')->make(['user_id' => $answer->user_id]));
            $answer->assignment->problems->each(function (Problem $problem) use ($answer) {
                /** @var Solution $solution */
                $answer->solutions()->save(factory(Solution::class)->make(['user_id' => $answer->user_id, 'problem_id' => $problem->id]));
            });
        });
    }
}
