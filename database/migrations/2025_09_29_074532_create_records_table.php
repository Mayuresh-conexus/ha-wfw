<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();

            // Existing relations you already use in your model
            $table->foreignId('patientid')->constrained('patients')->onDelete('cascade');
            
            // Store multiple doctor IDs in a JSON column
            $table->json('doctorid')->nullable();  // Store multiple doctor IDs

            // Store multiple GP IDs in a JSON column (allow multiple GPs per record)
            $table->json('gpid')->nullable();  // Store multiple GP IDs

            // Volunteer relation (if needed)
            $table->foreignId('volunteerid')->nullable()->constrained('users')->onDelete('set null');
            
            // Other relationships
            $table->foreignId('projectid')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('programid')->nullable()->constrained('programs')->onDelete('set null');

            // Add new standardized fields
            $table->string('record_type')->nullable();  // e.g. consultation, follow-up, etc.
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();  // Store app-uploaded data or file URLs

            // Status Enum and other fields
            $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('records');
    }
}
