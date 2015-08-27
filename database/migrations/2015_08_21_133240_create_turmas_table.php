<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('serie_id')->unsigned()->nullable();
            //$table->integer('serie_id');
			$table->string('letra',2);
            $table->timestamps();

        });

        Schema::table('turmas', function(Blueprint $table){
            $table->foreign('serie_id')->references('id')->on('series');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('turmas');
    }
}