<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();

            // Link to users table. If a user is deleted, their test records go too.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Store score as integer (e.g., 85 for 85%).
            $table->integer('score');

            // Keeping category indexed helps with generating quick stats/reports.
            $table->string('category')->nullable()->index();

            // Storing IDs as JSON is perfect for your history/review feature.
            $table->json('question_ids')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_results');
    }
};
