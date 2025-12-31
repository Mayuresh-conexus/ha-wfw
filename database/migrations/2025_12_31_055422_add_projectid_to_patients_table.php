<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Add nullable projectid column (safe for existing data)
            $table->unsignedBigInteger('projectid')->nullable()->after('programid');

            // Add foreign key constraint
            $table->foreign('projectid')
                ->references('id')
                ->on('projects')
                ->onDelete('set null'); // If project deleted, set patient.projectid to NULL
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['projectid']);

            // Then drop the column
            $table->dropColumn('projectid');
        });
    }
};
