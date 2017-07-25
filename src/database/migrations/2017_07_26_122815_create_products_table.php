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
            $table->increments('id');

            $table->unsignedInteger('productfeed_id')->index();

            $table->text('description')->nullable();
            $table->string('sku')->nullable();
            $table->text('link')->nullable(); // Data too long for column 'link'
            $table->double('price')->nullable();
            $table->double('price_old')->nullable();
            $table->text('title')->nullable();
            $table->string('accommodation_name')->nullable()->index();
            $table->string('accommodation_name_slug')->nullable()->index();
            $table->string('accommodation_type')->nullable()->index();
            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('duration_nights')->nullable();
            $table->string('airport_departure')->nullable();
            $table->date('departure_date')->nullable()->index();
            $table->string('destination_city')->nullable()->index();
            $table->string('destination_city_link')->nullable();
            $table->string('destination_region')->nullable();
            $table->string('destination_country')->nullable()->index();
            $table->string('travel_trip_type')->nullable();
            $table->unsignedTinyInteger('star_rating')->nullable()->index();
            $table->text('image')->nullable();

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
