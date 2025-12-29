<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer_text'); // The answer option text
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade'); // The question this answer belongs to
            $table->foreignId('next_question_id')->nullable()->constrained('questions')->onDelete('cascade'); // The next question to show based on the answer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
