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
            $table->string('psid')->unique();
            $table->string('fb_firstname')->nullable();
            $table->string('fb_lastname')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('host')->nullable();
            $table->string('access_token');
            $table->string('refresh_token')->nullable();
            $table->string('session')->nullable();
            $table->string('database_id')->nullable();
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
