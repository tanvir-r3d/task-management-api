<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'tanvir@skylarksoft.com',
        ], [
            'name' => 'Tanvir',
            'password' => Hash::make('123456'),
        ]);

        DB::table('task_statuses')->truncate();
        $statuses = [
            ['name' => 'Open'],
            ['name' => 'Pending'],
            ['name' => 'TODO'],
            ['name' => 'In Progress'],
            ['name' => 'Testing'],
            ['name' => 'Deploying'],
            ['name' => 'Closed'],
        ];
        TaskStatus::insert($statuses);
    }
}
