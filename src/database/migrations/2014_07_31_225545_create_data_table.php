<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('data', function(Blueprint $table)
		{
			// Keep the same (sort of) with your config-file
			$table->engine = 'InnoDB';
			$table->increments('id');
			
			$table->integer('program_id'); // Could be used for internal use
			$table->integer('feed_id'); // Could be used for internal use

			$table->string('title', 100);
			$table->string('link', 255);
			$table->text('description');
			$table->string('accommodation_name', 100);
				$table->string('slug_accommodation_name', 100);
			$table->string('accommodation_type', 50);
			$table->integer('min_nr_people');
			$table->string('location_description', 100);
			$table->integer('stars');

			$table->double('minimum_price');
			$table->double('maximum_price');
			$table->double('lowest_price');

			$table->string('continent_of_destination', 100);
				$table->string('slug_continent_of_destination', 100);
			$table->string('country_of_destination', 100);
				$table->string('slug_country_of_destination', 100);
			$table->string('country_link', 255);
			$table->string('region_of_destination', 100);
				$table->string('slug_region_of_destination', 100);
			$table->string('region_link', 255);
			$table->string('city_of_destination', 100);
				$table->string('slug_city_of_destination', 100);
			$table->string('city_link', 255);
			$table->string('longitude', 50);
			$table->string('latitude', 50);

			$table->string('continent_of_origin', 100);
				$table->string('slug_continent_of_origin', 100);
			$table->string('country_of_origin', 100);
				$table->string('slug_country_of_origin', 100);
			$table->string('city_of_origin', 100);
				$table->string('slug_city_of_origin', 100);
			$table->string('port_of_departure', 100);

			$table->string('img_small', 255);
			$table->string('img_medium', 255);
			$table->string('img_large', 255);

			$table->string('board_type', 50);
			$table->string('tour_operator', 50);
			$table->string('transportation_type', 50);
			$table->datetime('departure-date'); // to fix (see also 'config')
			$table->datetime('departure_date'); // to fix (see also 'config')
			$table->datetime('end_date'); // to fix (see also 'config')
			$table->integer('duration');


			$table->integer('daisycon_unique_id');
			$table->string('internal_id', 50);
			$table->integer('unique_integer');
			$table->string('update_hash', 100);

			// $table->string('priority', 50);
			// $table->string('usp', 255);
			
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
		Schema::drop('data');
	}

}
