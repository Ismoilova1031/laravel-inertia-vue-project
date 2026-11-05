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
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('attempt');
            $table->unsignedTinyInteger('status')->default(0);
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->text('teacher_comment')->nullable();
            $table->dateTime('submitted_at');
            $table->timestamps();

            $table->unique(['task_id', 'student_id', 'attempt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
