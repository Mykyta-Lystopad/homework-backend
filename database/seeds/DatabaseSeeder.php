<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Users
     * 1-5 teachers
     * 6-25 students
     * 26-30 parents
     *
     * @return void
     */
    public function run()
    {
        Storage::deleteDirectory('attachments');
        Storage::deleteDirectory('qrCodes');
        $this->call(UserSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(AssignmentSeeder::class);
        $this->call(ProblemSeeder::class);
        $this->call(SolutionSeeder::class);
        $this->call(MessageSeeder::class);
    }
}
