<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            // Indexing the category makes filtering questions MUCH faster
            $table->enum('category', ['Verbal', 'Numerik', 'Logika', 'Spasial'])->index();

            $table->text('content');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');

            // Nullable allows questions without images
            $table->string('image_path')->nullable();

            // Using char(1) is perfect for storing 'A', 'B', 'C', or 'D'
            $table->char('correct_answer', 1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
