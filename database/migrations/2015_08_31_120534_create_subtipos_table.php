<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtipos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tipo_id')->index();
            $table->foreign('tipo_id')->references('id')->on('tipos')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->text('descripcion')->default('');
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
        Schema::drop('subtipos');
    }
}
