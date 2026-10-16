<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TestResult;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestResultSeeder extends Seeder
{
    // --- GENERATE USER ACCOUNTS & TEST RESULTS ---
    public function run(): void
    {
        // 1. Create some dummy users
        $users = [
            ['name' => 'Alex Rivera',   'email' => 'alex@example.com',   'password' => Hash::make('password123'), 'is_admin' => false],
            ['name' => 'Budi Santoso',  'email' => 'budi@example.com',  'password' => Hash::make('password123'), 'is_admin' => false],
            ['name' => 'Chandra Malik', 'email' => 'chandra@example.com', 'password' => Hash::make('password123'), 'is_admin' => false],
            ['name' => 'Diana Putri',   'email' => 'diana@example.com',   'password' => Hash::make('password123'), 'is_admin' => false],
            ['name' => 'Siti Aminah',   'email' => 'siti@example.com',   'password' => Hash::make('password123'), 'is_admin' => false],
        ];

        // 2. These scores and categories are mixed up so we can see how
        // the "Pass/Fail" badges and category colors look on the UI.
        $scores     = [88, 42, 75, 95, 58];
        $categories = ['Verbal', 'Numerik', 'Logika', 'Spasial', 'Numerik'];

        foreach ($users as $index => $userData) {

            // Use firstOrCreate so we don't end up with duplicate users if we run this seed twice.
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Grab the IDs of the questions that match the category for this result.
            $category    = $categories[$index];
            $questionIds = Question::where('category', $category)->pluck('id')->toArray();

            // Finally, save the test result for this user.
            TestResult::create([
                'user_id'      => $user->id,
                'score'        => $scores[$index],
                'category'     => $category,
                'question_ids' => $questionIds,
            ]);
        }
    }
}
