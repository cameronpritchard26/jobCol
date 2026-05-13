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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('about');
        });

        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('profile_picture');
        });

        Schema::table('employer_profiles', function (Blueprint $table) {
            $table->dropColumn('profile_picture');
        });
    }
};
