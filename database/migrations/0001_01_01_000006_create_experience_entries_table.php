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
        Schema::create('experience_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('company');
            $table->integer('start_month');
            $table->integer('start_year');
            $table->integer('end_month')->nullable();
            $table->integer('end_year')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience_entries');
    }
};
