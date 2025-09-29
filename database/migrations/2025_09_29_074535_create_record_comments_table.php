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
        Schema::create('record_comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('userid')->constrained('users');
    $table->foreignId('recordid')->constrained('records');
    $table->text('description');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_comments');
    }
};
