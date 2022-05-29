<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("user_id");

            $table->string('store_name')->nullable();
            $table->integer("shipping_cost")->default(0);
            $table->boolean("vat_included")->default(0);
            $table->integer("vat_percentage")->default(0);

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
        Schema::dropIfExists('merchant_profiles');
    }
}
