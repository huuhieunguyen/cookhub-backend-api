<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('grocery_ingredients', function (Blueprint $table) {
            $table->dropForeign(['grocery_recipe_id']);
            $table->foreign('grocery_recipe_id')->references('id')->on('grocery_recipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grocery_ingredients', function (Blueprint $table) {
            $table->dropForeign(['grocery_recipe_id']);
            $table->foreign('grocery_recipe_id')->references('id')->on('grocery_recipes');
        });
    }
};
