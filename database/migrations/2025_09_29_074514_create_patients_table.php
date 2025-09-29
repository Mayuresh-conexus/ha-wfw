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
            $table->float('weight')->nullable();
            $table->boolean('smoke')->default(false);
            $table->boolean('drinkalcohol')->default(false);
            $table->text('hobbies')->nullable();
            $table->text('reasonvisit')->nullable();
            $table->string('bp')->nullable();
            $table->string('heartrate')->nullable();
            $table->string('occupation')->nullable();
            $table->string('gender')->nullable();
            $table->boolean('isactive')->default(true);
            $table->string('profile')->nullable();
            $table->text('reasontovisit')->nullable();
            $table->text('existingmedicalcondition')->nullable();
            $table->text('existingmedicalhistory')->nullable();
            $table->text('existingmedication')->nullable();
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
