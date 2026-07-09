<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('records', function (Blueprint $table) {
            // Clinical/patient status set by the care team after audit
            // (Normal, Critical, …). Separate from the `status` workflow column
            // (draft/submitted/reviewed).
            $table->string('patient_status')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('records', function (Blueprint $table) {
            $table->dropColumn('patient_status');
        });
    }
};
