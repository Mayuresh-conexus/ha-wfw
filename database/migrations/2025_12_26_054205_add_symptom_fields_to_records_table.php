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
        Schema::table('records', function (Blueprint $table) {
            // Store selected symptom IDs as JSON array (e.g., [1, 2, 5])
            $table->json('symptom_ids')->nullable()->after('record_type');

            // Store full question-answer summary in JSON format (large data)
            $table->longText('question_summary')->nullable()->after('symptom_ids');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn(['symptom_ids', 'question_summary']);
        });
    }
};
