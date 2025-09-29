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
       Schema::create('body_sections', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->boolean('isactive')->default(true);
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
        Schema::dropIfExists('body_sections');
    }
};
