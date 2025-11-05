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
    Schema::create('records', function (Blueprint $table) {
        $table->id();

        // Existing relations you already use in your model
        $table->foreignId('patientid')->constrained('patients')->onDelete('cascade');
        $table->foreignId('doctorid')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('volunteerid')->nullable()->constrained('users')->onDelete('set null');
        $table->foreignId('projectid')->nullable()->constrained('projects')->onDelete('set null');
        $table->foreignId('programid')->nullable()->constrained('programs')->onDelete('set null');

        // Add new standardized fields
        $table->string('record_type')->nullable(); // e.g. consultation, follow-up, etc.
        $table->text('notes')->nullable();
        $table->json('attachments')->nullable(); // store app-uploaded data or file URLs

        $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
        $table->timestamp('submitted_at')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
