<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_requests', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->string('course_year');
            $table->string('document_type');
            $table->text('purpose');
            $table->string('status')->default('Pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->string('released_to')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_requests');
    }
};
