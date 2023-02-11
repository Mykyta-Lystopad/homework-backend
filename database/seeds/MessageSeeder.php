<?php

use App\Models\Answer;
use App\Models\Assignment;
use App\Models\Attachment;
use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Assignment::with('answers')->get()->each(function (Assignment $assignment) {
            $assignment->answers()->each(function (Answer $answer) use ($assignment) {
                $answer->messages()->save(factory(Message::class)->make([
                    'user_id' => $assignment->user_id
                ]));
                $answer->messages()->save(factory(Message::class)->make([
                    'user_id' => $answer->user_id
                ]));
            });
        });
    }
}
