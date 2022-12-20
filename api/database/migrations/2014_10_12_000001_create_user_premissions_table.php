<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPremissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->boolean('users')->default(false);
            $table->boolean('pages')->default(true);
            $table->boolean('cases')->default(true);
            $table->boolean('slider')->default(true);
            $table->boolean('images')->default(true);
            $table->boolean('solutions')->default(false);
            $table->boolean('inbox')->default(false);
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
        Schema::dropIfExists('user_permissions');
    }
}
