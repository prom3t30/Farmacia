<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pedido'); // Cambiado a integer (sin unsigned)
            $table->string('numero_factura')->unique();
            $table->dateTime('fecha_emision');
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('emitida');
            $table->timestamps();

            // Relaciones
            $table->foreign('id_pedido')->references('id')->on('pedidos')->onDelete('cascade');
        });
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturas');
    }
}
