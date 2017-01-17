<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            // Llave foranea en Products
            $table->integer('product_id')
                  ->unsigned();
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
            // Atributos
            // Nombre
            $table->text('title');
            // Imagen principal
            $table->text('img_url')->nullable();
            // Imagen secundaria
            $table->text('img_set')->nullable();
            // Marca (si la hay)
            $table->string('brand', 100)->nullable();
            // Departamento
            $table->string('departament', 100)->nullable();
            // Descripción
            $table->text('feature')->nullable();
            // Id de la oferta
            $table->text('offer')->nullable();
            // Peso !importante
            $table->decimal('weight', 8, 2)->nullable();
            // Precio en dolares sin formato
            $table->decimal('price', 8, 2)->nullable();
            // Costo puesto en colombia
            $table->decimal('costo_co', 8, 2)->nullable();
            // Costo puesto en colombia con utilidad
            $table->decimal('costo_ut', 8, 2)->nullable();
            // Costo puesto en colombia con utilidad, iva y comision de ml
            $table->decimal('costo_final_ml', 8, 2)->nullable();
            // Costo puesto en colombia con utilidad, iva y comision de li
            $table->decimal('costo_final_li', 8, 2)->nullable();
            // Timestaps
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
        Schema::dropIfExists('product_details');
    }
}
