<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmazonSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amazon_searches', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('search')->unique();
            $table->integer('page');
            $table->integer('total_pages');

            $table->string('keywords')->nullable();
            $table->string('category')->nullable();
            $table->boolean('prime')->nullable();

            $table->string('node')->nullable();
            $table->string('child')->nullable();

            $table->boolean('meli')->default(false);
            $table->boolean('linio')->default(false);

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
        Schema::dropIfExists('amazon_searches');
    }
}
