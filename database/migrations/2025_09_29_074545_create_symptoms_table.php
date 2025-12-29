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
        Schema::create('symptoms', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('body_section_id') // Foreign key to the body_sections table
                  ->constrained('body_sections')  // References the body_sections table
                  ->onDelete('cascade'); 
    $table->boolean('is_active')->default(true);
    $table->string('tag')->nullable();
    $table->boolean('iscritical')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptoms');
    }
};
