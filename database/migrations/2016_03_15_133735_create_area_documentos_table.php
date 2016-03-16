<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.area_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned();
            $table->integer('id_tipo_documento')->unsigned();
            $table->string('tipo_archivo');
            $table->string('path');
            $table->string('thumbnail_path');
            $table->integer('id_usuario')->unsigned();
            $table->timestamps();
            
            $table->foreign('id_area')
                ->references('id')
                ->on('Equipamiento.areas')
                ->onDelete('cascade');
            
            $table->foreign('id_tipo_documento')
                ->references('id')
                ->on('Equipamiento.tipos_documento')
                ;
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.area_documentos');
    }
}
