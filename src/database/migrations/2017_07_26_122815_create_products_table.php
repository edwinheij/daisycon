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

            $table->string('accommodation_address')->nullable();
            $table->unsignedInteger('accommodation_bathrooms')->nullable();
            $table->unsignedInteger('accommodation_bedrooms')->nullable();
            $table->unsignedInteger('accommodation_child_friendly')->nullable();
            $table->unsignedInteger('accommodation_floors')->nullable();
            $table->date('accommodation_lowest_date')->nullable();
            $table->string('accommodation_lowest_price')->nullable();
            $table->string('accommodation_name')->nullable()->index();
            $table->string('accommodation_name_slug')->nullable()->index();
            $table->string('accommodation_on_holiday_park')->nullable();
            $table->string('accommodation_pets_allowed')->nullable();
            $table->string('accommodation_rooms')->nullable();
            $table->string('accommodation_smoking_allowed')->nullable();
            $table->string('accommodation_sqm_floors')->nullable();
            $table->string('accommodation_toilets')->nullable();
            $table->string('accommodation_type')->nullable();

            $table->string('additional_costs')->nullable();

            $table->string('airline')->nullable();
            $table->string('airline_code')->nullable();
            $table->string('airline_code_return')->nullable();
            $table->string('airline_return')->nullable();
            $table->string('airport_departure')->nullable()->index();
            $table->string('airport_departure_return')->nullable();
            $table->string('airport_destination')->nullable()->index();
            $table->string('airport_destination_return')->nullable();
            $table->string('airport_stapover_1')->nullable();
            $table->string('airport_stapover_2')->nullable();
            $table->string('airportcode_departure')->nullable()->index();
            $table->string('airportcode_departure_return')->nullable()->index();
            $table->string('airportcode_destination')->nullable()->index();
            $table->string('airportcode_destination_return')->nullable()->index();
            $table->string('airportcode_stapover_1')->nullable();
            $table->string('airportcode_stapover_2')->nullable();

            $table->date('arrival_date')->nullable();
            $table->unsignedTinyInteger('available')->nullable();
            $table->date('available_from')->nullable();

            $table->string('brand')->nullable();
            $table->string('brand_logo')->nullable();
            $table->string('category')->nullable();
            $table->string('category_path')->nullable();
            $table->string('color_primary')->nullable();
            $table->string('condition')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();

            $table->string('daisycon_unique_id')->nullable()->unique()->index();

            // daisycon_unique_id
            // daisycon_unique_id_modified
            // daisycon_unique_id_since
            // data_hash
            // delete_date

            $table->text('delivery_description')->nullable();
            $table->string('delivery_time')->nullable();

            $table->string('departure_city')->nullable()->index();
            $table->string('departure_country')->nullable();
            $table->date('departure_date')->nullable()->index();
            $table->date('departure_date_return')->nullable()->index();
            $table->string('departure_latitude')->nullable();
            $table->string('departure_longitude')->nullable();
            $table->string('departure_port')->nullable();
            $table->string('departure_times')->nullable();

            $table->text('description')->nullable();
            $table->string('description_short')->nullable();

            $table->string('destination_city')->nullable()->index();
            $table->string('destination_city_slug')->nullable()->index();
            $table->text('destination_city_link')->nullable();
            $table->string('destination_continent')->nullable()->index();
            $table->string('destination_country')->nullable()->index();
            $table->string('destination_country_slug')->nullable()->index();
            $table->text('destination_country_description')->nullable();
            $table->text('destination_country_link')->nullable();
            $table->string('destination_language')->nullable();
            $table->string('destination_latitude')->nullable();
            $table->text('destination_location_description')->nullable();
            $table->string('destination_longitude')->nullable();
            $table->string('destination_port')->nullable();
            $table->string('destination_region')->nullable()->index();
            $table->string('destination_region_slug')->nullable()->index();
            $table->text('destination_region_link')->nullable();
            $table->string('destination_zipcode')->nullable();

            $table->string('discount_amount')->nullable();
            $table->string('discount_percentage')->nullable();

            $table->unsignedInteger('distance_to_bakery')->nullable();
            $table->unsignedInteger('distance_to_beach')->nullable();
            $table->unsignedInteger('distance_to_citycenter')->nullable();
            $table->unsignedInteger('distance_to_golfcourse')->nullable();
            $table->unsignedInteger('distance_to_restaurant')->nullable();
            $table->unsignedInteger('distance_to_shopping')->nullable();
            $table->unsignedInteger('distance_to_swimwater')->nullable();

            $table->string('duration_days')->nullable()->index();
            $table->string('duration_nights')->nullable()->index();
            $table->string('ean')->nullable();
            $table->string('gender_target')->nullable();
            $table->string('google_category_id')->nullable();
            // id (program id)

            $table->unsignedTinyInteger('has_airco')->nullable();
            $table->unsignedTinyInteger('has_barbecue')->nullable();
            $table->unsignedTinyInteger('has_child_chair')->nullable();
            $table->unsignedTinyInteger('has_dishwasher')->nullable();
            $table->unsignedTinyInteger('has_electricity')->nullable();
            $table->unsignedTinyInteger('has_garage')->nullable();
            $table->unsignedTinyInteger('has_garden')->nullable();
            $table->unsignedTinyInteger('has_heating')->nullable();
            $table->unsignedTinyInteger('has_internet')->nullable();
            $table->unsignedTinyInteger('has_livingroom')->nullable();
            $table->unsignedTinyInteger('has_microwave')->nullable();
            $table->unsignedTinyInteger('has_playground')->nullable();
            $table->unsignedTinyInteger('has_sauna')->nullable();
            $table->unsignedTinyInteger('has_swimmingpool')->nullable();
            $table->unsignedTinyInteger('has_telephone')->nullable();
            $table->unsignedTinyInteger('has_television')->nullable();
            $table->unsignedTinyInteger('has_washingmachine')->nullable();

            $table->text('image_large')->nullable();
            $table->text('image_medium')->nullable();
            $table->text('image_small')->nullable();
            $table->unsignedInteger('in_stock')->nullable();
            $table->unsignedInteger('in_stock_amount')->nullable();
            // insert_date
            $table->text('keywords')->nullable();
            // last_modified
            $table->text('link');

            $table->unsignedTinyInteger('max_nr_people')->nullable();

            $table->string('model')->nullable();
            $table->string('name')->nullable();
            // previous_daisycon_unique_id
            $table->string('price')->nullable()->index();
            $table->string('price_old')->nullable();
            $table->string('price_shipping')->nullable();
            $table->string('priority')->nullable();
            $table->unsignedInteger('product_count')->nullable();
            $table->unsignedInteger('productfeed_id')->nullable();
            $table->string('size')->nullable();
            $table->string('size_description')->nullable();
            $table->string('sku')->nullable();
            // status
            $table->text('terms_conditions')->nullable();
            $table->string('title')->nullable();
            $table->string('title_slug')->nullable()->index();
            $table->string('travel_tour_operator')->nullable();

            $table->string('travel_trip_type')->nullable();

            $table->string('travel_transportation_type')->nullable()->index();

            $table->string('trip_holiday_type')->nullable();

            $table->string('trip_lastminute')->nullable();

            // update_date

            $table->unsignedTinyInteger('star_rating')->nullable()->index();

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
