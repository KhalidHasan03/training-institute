<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->date('enrollment_date')->default(now()->toDateString());
            $table->decimal('course_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('final_fee', 12, 2)->default(0);
            $table->string('payment_status', 20)->default('unpaid');
            $table->string('status', 20)->default('active');
            $table->timestamps();

            $table->unique(['student_id', 'batch_id']);
            $table->index(['status', 'payment_status']);
            $table->index(['batch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};