<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
    $table->id();
    $table->string('label');
    $table->string('value')->nullable();
    $table->boolean('isterminated')->default(false);
    $table->string('tag')->nullable();
    $table->boolean('iscritical')->default(false);
    $table->foreignId('questionid')->constrained('questions');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
