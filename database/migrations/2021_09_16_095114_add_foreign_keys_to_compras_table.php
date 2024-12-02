<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToComprasTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('compras', function (Blueprint $table) {
			$table->foreign('id_pedido', 'compras_fk')->references('id')->on('pedidos')
			->onDelete('cascade')
			->onUpdate('cascade')
			;

			$table->foreign('id_farmacia')->references('id')->on('farmacias')
			->onDelete('cascade')
			->onUpdate('cascade')
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
		Schema::table('compras', function (Blueprint $table) {
			$table->dropForeign('compras_fk');
		});
	}
}
