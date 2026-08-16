<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_session_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('status', 20)->default('present');
            $table->timestamps();

            $table->unique(['class_session_id', 'student_id'], 'attendance_unique_session_student');
            $table->index(['batch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
