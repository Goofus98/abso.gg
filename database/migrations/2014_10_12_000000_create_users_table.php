<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('steam_id', 32)->unique();
            $table->string('name', 255);
            $table->string('rank', 30)->nullable();
            $table->unsignedInteger('playtime')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('avatar_frame')->nullable();
            $table->timestamp('last_steam_update')->nullable();
            $table->rememberToken();
            $table->timestamps();
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
}
