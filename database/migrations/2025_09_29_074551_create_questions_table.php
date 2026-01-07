<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symptomid')->nullable()->constrained('symptoms');
            $table->text('question_text'); // The 
            $table->string('gender')->nullable();
            $table->json('answers'); // Store answers and next_question_id
            $table->string('question_index')->nullable(); // Question index
            $table->boolean('is_active')->default(true); // Is the question active?
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}

