<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('id_number')->unique(); // This is the actual student ID
            $table->string('full_name');
            $table->string('course_year')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_summaries');
    }
};
