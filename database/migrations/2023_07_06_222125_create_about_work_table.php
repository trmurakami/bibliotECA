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
        Schema::create('about_work', function (Blueprint $table) {
            $table->id();
            $table->foreignId('about_id')->constrained();
            $table->foreignId('work_id')->constrained();
            $table->timestamps();
            $table->index(['about_id', 'work_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_work');
    }
};