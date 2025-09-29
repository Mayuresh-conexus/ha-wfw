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
        Schema::create('scheduled_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('records');
            $table->foreignId('volunteer_id')->constrained('users');
            $table->foreignId('assigned_gp_doctor')->constrained('users');
            $table->date('schedule_date');
            $table->time('schedule_start_time');
            $table->time('schedule_end_time');
            $table->string('room_name');
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_calls');
    }
};
