<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonWorkTable extends Migration
{
    public function up()
    {
        Schema::create('person_work', function (Blueprint $table) {
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('work_id');
            $table->timestamps();
            
            $table->primary(['person_id', 'work_id']);
            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('person_work');
    }
}