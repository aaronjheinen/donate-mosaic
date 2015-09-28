<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetsContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('set_id');
            $table->longText('header')->nullable();
            $table->longText('footer')->nullable();
            $table->longText('disclaimer')->nullable();
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
        Schema::drop('set_content');
    }
}
