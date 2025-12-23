<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGmodServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmod_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->ipAddress('ip')->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->unsignedInteger('online')->default(0);
            $table->unsignedInteger('max_online')->default(128);
            $table->string('gamemode')->default("sandbox");
            $table->string('map')->default("gm_flatgrass");
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
        Schema::dropIfExists('gmod_servers');
    }
}
