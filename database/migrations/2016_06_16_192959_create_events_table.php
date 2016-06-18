<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user')->unsigned();
            $table->integer('week')->unsigned();
            $table->integer('year')->unsigned();
            $table->integer('day')->unsigned();
            $table->integer('hour')->unsigned();
            $table->integer('event_type_id')->unsigned();
            $table->integer('room_id')->unsigned();
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('event_type_id')->references('id')->on('event_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('events');
    }
}
