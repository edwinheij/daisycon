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

//            $table->unsignedInteger('product_id')->nullable()->index();
            $table->unsignedInteger('program_id')->nullable()->index(); // as 'id' in daisycon-data
            $table->string('daisycon_unique_id')->nullable()->unique()->index();
            $table->string('daisycon_unique_id_modified')->nullable()->index();
            $table->string('daisycon_unique_id_since')->nullable()->index();
            $table->string('previous_daisycon_unique_id')->nullable();
            $table->string('data_hash')->nullable();
            $table->string('status')->nullable();
            $table->date('insert_date')->nullable();
            $table->date('update_date')->nullable()->index();
            $table->date('delete_date')->nullable();
            $table->date('last_modified')->nullable();

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
