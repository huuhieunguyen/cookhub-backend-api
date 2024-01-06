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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id');
            $table->string('cover_url')->nullable();
            $table->string('title');
            $table->text('desc');
            $table->float('rating')->default(0);
            $table->integer('cook_time')->default(0);
            $table->enum('level', ['easy', 'medium', 'hard', 'masterchef'])->default('medium');
            $table->integer('review_count')->default(0);
            $table->string('regional')->nullable();
            $table->string('dish_type')->nullable();
            $table->integer('serves')->default(1);
            $table->timestamps();

            $table->foreign('author_id')->references('id')->on('users'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipes');
    }
};
