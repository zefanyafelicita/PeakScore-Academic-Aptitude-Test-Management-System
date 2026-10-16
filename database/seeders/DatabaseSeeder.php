<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    // --- MAIN SEEDER RUNNER ---
    public function run(): void
    {
        // 1. Create the master admin account for system control :D
        $admin = new User();
        $admin->name     = 'Master Admin';
        $admin->email    = 'admin@tpa.com';
        $admin->password = Hash::make('password123');
        $admin->is_admin = true;
        $admin->save();

        // 2. Call the other seeders.
        // Order matters here! We need questions to exist before we can link them to results.
        $this->call([
            QuestionSeeder::class,
            TestResultSeeder::class,
        ]);
    }
}
