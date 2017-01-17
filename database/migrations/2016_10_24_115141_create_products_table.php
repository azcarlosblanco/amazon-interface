<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            // Ids de los productos en sus respectivas plataformas
            $table->string('amazon_id', 100)->unique();
            $table->string('ml_id', 100)->nullable();
            $table->string('li_id', 100)->nullable();
            // Estado de los produ en sus respectivas plataformas (p = publicado para ml y li) (c = chopping card para amazon)
            $table->boolean('amazon_c')->default(false);
            $table->boolean('ml_p')->default(false);
            $table->boolean('li_p')->default(false);
            // Estado de cambio del prducto en amazon para su actualizacion en ml o li
            $table->boolean('changed')->default(false);
            // Timestamps
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
        Schema::dropIfExists('products');
    }
}
