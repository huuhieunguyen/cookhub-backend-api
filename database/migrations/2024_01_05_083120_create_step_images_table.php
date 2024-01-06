<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('step_id');
            $table->string('image_url');
            $table->timestamps();

            $table->foreign('step_id')->references('id')->on('steps'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('step_images');
    }
};
