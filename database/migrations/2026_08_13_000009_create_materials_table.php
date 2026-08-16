<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('type', 30)->default('document');
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['batch_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
