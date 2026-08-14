<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('payment_method', 30);
            $table->string('transaction_id')->nullable();
            $table->string('notes')->nullable();
            $table->string('status', 20)->default('completed');
            $table->timestamps();

            $table->index(['student_id', 'payment_date']);
            $table->index(['enrollment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};