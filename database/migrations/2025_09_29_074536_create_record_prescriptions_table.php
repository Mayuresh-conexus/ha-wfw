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
        Schema::create('record_prescriptions', function (Blueprint $table) {
            $table->id();

            // Link to parent record
            $table->foreignId('recordid')
                ->constrained('records')
                ->onDelete('cascade');

            // Add your prescription fields
            $table->string('medicine_name')->nullable();
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->text('instructions')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_prescriptions');
    }
};
