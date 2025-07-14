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
            $table->string('api_key')->unique();
            $table->timestamp('last_seen_at')->nullable();
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
