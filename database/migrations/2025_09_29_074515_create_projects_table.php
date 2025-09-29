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
        Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->foreignId('cityid')->constrained('cities');
    $table->foreignId('stateid')->constrained('states');
    $table->foreignId('countryid')->constrained('countries');
    $table->boolean('isactive')->default(true);
    $table->date('startdate')->nullable();
    $table->date('enddate')->nullable();
    $table->double('budget')->nullable();
    $table->foreignId('programid')->nullable()->constrained('programs');
    $table->string('othercity')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
