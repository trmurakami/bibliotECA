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
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->foreign('about_id')->references('id')->on('abouts')->onDelete('cascade');
            $table->timestamps();
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