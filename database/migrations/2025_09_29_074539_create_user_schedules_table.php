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
       Schema::create('user_schedules', function (Blueprint $table) {
    $table->id();
    $table->foreignId('userid')->constrained('users');
    $table->string('weekname');
    $table->boolean('ismorning')->default(false);
    $table->boolean('isafternoon')->default(false);
    $table->boolean('isevening')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_schedules');
    }
};
