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
        Schema::create('things', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('id_lattes13')->nullable();
            $table->string('name');
            $table->jsonb('affiliation')->nullable();
            $table->timestamps();

            $table->collation = 'utf8mb4_unicode_ci';

            $table->index('name', 'affiliation', 'id_lattes13');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('things');
    }
};