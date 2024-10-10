<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password'=> '123'
        ]);
        User::factory(100)->create();

        Task::factory()->count(1000)->create();

    }
}
