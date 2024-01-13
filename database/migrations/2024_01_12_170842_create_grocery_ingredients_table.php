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
        Schema::create('grocery_ingredients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('grocery_recipe_id');
            $table->string('name');
            $table->float('amount');
            $table->string('unit');
            $table->boolean('status')->default(false);
            $table->timestamps();

            $table->foreign('grocery_recipe_id')->references('id')->on('grocery_recipes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grocery_ingredients');
    }
};
