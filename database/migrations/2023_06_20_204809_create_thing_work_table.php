<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThingWorkTable extends Migration
{
    public function up()
    {
        Schema::create('thing_work', function (Blueprint $table) {
            $table->unsignedBigInteger('thing_id');
            $table->unsignedBigInteger('work_id');
            $table->timestamps();
            $table->primary(['thing_id', 'work_id']);
            $table->foreign('thing_id')->references('id')->on('things')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->string('function')->nullable();
            $table->string('affiliation')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thing_work');
    }
}