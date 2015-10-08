<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTransaccionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Equipamiento.items_transaccion', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_material')->index();
            $table->decimal('cantidad', 12, 2);
            $table->decimal('precio', 12, 2)->default(0);
            $table->integer('id_area_origen')->nullable()->index();
            $table->integer('id_area_destino')->index();
            $table->integer('id_transaccion')->index();
            $table->string('tipo_transaccion')->index();
            $table->timestamps();

            $table->foreign('id_material')
                ->references('id_material')
                ->on('materiales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Equipamiento.items_transaccion');
    }
}
