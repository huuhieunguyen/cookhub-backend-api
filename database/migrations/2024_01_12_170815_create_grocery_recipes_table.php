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
        Schema::create('grocery_recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('recipe_id');
            $table->date('date_saved');
            $table->integer('day_of_week');
            $table->string('cover_url')->nullable();
            $table->string('title');
            $table->text('desc');
            $table->integer('cook_time');
            $table->enum('level', ['easy', 'medium', 'hard', 'masterchef'])->default('medium');
            $table->string('regional')->nullable();
            $table->string('dish_type')->nullable();
            $table->integer('serves');
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
            $table->foreign('recipe_id')->references('id')->on('recipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grocery_recipes');
    }
};
