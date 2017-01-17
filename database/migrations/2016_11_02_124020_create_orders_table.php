<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->bigInteger('user_id');
            $table->string('resource', 200)->nullable();
            $table->string('topic', 100)->nullable();

            // despues de llamar a Mercadolibre

            $table->integer('order_id')->nullable();
            $table->string('status', 100)->nullable();
            $table->string('product_id')->nullable();

            $table->string('nickname', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->text('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('total_amount')->nullable();
            $table->integer('paid_amount')->nullable();

            // para que el vendedor llene los datos.
            $table->string('actual_nickname', 100)->nullable();
            $table->string('actual_phone')->nullable();
            $table->text('shipping_details')->nullable();
            $table->text('observations')->nullable();

            // status
            $table->boolean('processed')->default(false);
            $table->boolean('rejected')->default(false);

            // despues de guardar en el carrito de amazon
            $table->string('cart_id')->nullable();


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
        Schema::dropIfExists('orders');
    }
}
