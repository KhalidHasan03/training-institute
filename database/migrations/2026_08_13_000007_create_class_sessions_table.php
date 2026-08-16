<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('topic')->nullable();
            $table->string('room')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('scheduled');
            $table->timestamps();

            $table->index(['batch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
