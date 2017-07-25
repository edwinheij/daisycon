<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productinfo', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id')->index()->nullable();
            $table->string('daisycon_unique_id')->index()->nullable();
            $table->string('previous_daisycon_unique_id')->nullable();
            $table->string('data_hash')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('insert_date')->nullable();
            $table->dateTime('update_date')->index()->nullable();
            $table->dateTime('delete_date')->nullable();

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
        Schema::dropIfExists('productinfo');
    }
}
