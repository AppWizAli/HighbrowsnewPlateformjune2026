<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
       public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('subject'); // Subject of the question
            $table->string('grade');   // Grade (e.g., 5, 6, 7, or 'Issb')
            $table->text('question');  // Question text

            // Image fields for the question and options
            $table->string('question_image')->nullable();  // Image for the question
            $table->json('option_images')->nullable();   // JSON to store images for each option (A, B, C, D, E)

            $table->json('options')->nullable();   // JSON to store the multiple options (A, B, C, D, E)
            $table->string('correct_answer'); // Correct answer (e.g., 'A', 'B', 'C', etc.)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
};
