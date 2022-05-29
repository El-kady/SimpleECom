<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductI18nsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_i18ns', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("product_id");

            $table->string('lang');
            $table->string('title');
            $table->text('description');
            $table->integer('price');
            $table->string('currency');

            $table->unique([
                'product_id',
                'lang'
            ], 'product_land');

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
        Schema::dropIfExists('product_i18ns');
    }
}
