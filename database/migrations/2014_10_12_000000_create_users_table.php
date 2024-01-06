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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('email_verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->string('phone_number')->nullable()->regex('/^[0-9]+$/');
            $table->string('avatar_url')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('intro')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active_time')->nullable();
            $table->integer('inactice_duration')->nullable()->default(0);

            $table->integer('count_followees')->default(0);
            $table->integer('count_followers')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
