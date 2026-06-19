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
        Schema::table('patients', function (Blueprint $table) {
            $table->index('programid');
            $table->index('created_at');
        });

        Schema::table('records', function (Blueprint $table) {
            $table->index('patientid');
            $table->index('projectid');
            $table->index('programid');
            $table->index('volunteerid');
            $table->index('created_at');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('programid');
            $table->index('volunteerid');
            $table->index('is_active');
        });

        Schema::table('scheduled_calls', function (Blueprint $table) {
            $table->index('recordid');
            $table->index('schedule_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
