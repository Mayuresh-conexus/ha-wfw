<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_calls', function (Blueprint $table) {
            $table->id();

            // Link to Record
            $table->foreignId('recordid')
                ->constrained('records')
                ->onDelete('cascade');

            // Volunteer user
            $table->foreignId('volunteer_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // GP / Doctor user
            $table->foreignId('assigned_gp_doctor_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->date('schedule_date');
            $table->time('schedule_start_time');
            $table->time('schedule_end_time');
            $table->string('room_name');
            $table->string('status')->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_calls');
    }
};
