<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;  

class CreateGmodBansTable extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gmod_bans', function (Blueprint $table) {
            $table->id();

            $table->string('SteamID', 32);
            $table->string('Reason', 255)->nullable();
            $table->string('Type', 32)->nullable();
            $table->string('Admin', 32)->nullable();

            $table->unsignedInteger('ExpiryDate')->nullable();

            $table->boolean('Revoked')->default(false);
            $table->string('Revoker', 32)->nullable();
            $table->string('RevokeReason', 255)->nullable();
            $table->boolean('ReasonEdited')->default(false);
            $table->boolean('ExpiryDateEdited')->default(false);

            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->foreign('SteamID')
                  ->references('steam_id')
                  ->on('users');

            $table->foreign('Admin')
                  ->references('steam_id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gmod_bans');
    }
}
