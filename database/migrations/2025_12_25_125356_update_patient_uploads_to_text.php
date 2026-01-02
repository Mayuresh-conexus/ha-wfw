<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('generalhealthupload')->change();
            $table->text('medicationupload')->change();
            $table->text('malariatestupload')->change();
            $table->text('hivtestupload')->change();
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('generalhealthupload', 255)->change();
            $table->string('medicationupload', 255)->change();
            $table->string('malariatestupload', 255)->change();
            $table->string('hivtestupload', 255)->change();
        });
    }
};
