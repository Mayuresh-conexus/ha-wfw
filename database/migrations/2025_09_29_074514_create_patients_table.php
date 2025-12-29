<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('filenumber')->unique();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->date('dob')->nullable();
            $table->float('height')->nullable();
            $table->text('heightunit')->nullable();
            $table->float('weight')->nullable();
            $table->text('weightunit')->nullable();
            $table->boolean('smoke')->default(false);
            $table->boolean('drinkalcohol')->default(false);
            $table->text('generalhealth')->nullable();
            $table->string('generalhealthupload')->nullable();
            $table->text('reasonvisit')->nullable();
            $table->string('bp')->nullable();
            $table->string('heartrate')->nullable();
            $table->string('occupation')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('profile')->nullable();
            $table->text('reasontovisit')->nullable();
            $table->text('medication')->nullable();
            $table->string('medicationupload')->nullable();
            $table->text('familyhealthreason')->nullable();
            $table->text('malariatest')->nullable();
            $table->string('malariatestupload')->nullable();
            $table->text('hivtest')->nullable();
            $table->string('hivtestupload')->nullable();
            $table->string('preferredphysician')->nullable();
            $table->float('oxygensaturation')->nullable();
            $table->float('temperature')->nullable();
            $table->text('additionalcomment')->nullable();
            $table->foreignId('programid')->nullable()->constrained('programs');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
