<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFulltextIndexOnDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE data ADD FULLTEXT data_search(accommodation_name, description)');
		Schema::table('data', function($table){
			$table->index('slug_continent_of_destination');
			$table->index('slug_country_of_destination');
			$table->index('slug_region_of_destination');
			$table->index('slug_city_of_destination');
			$table->index('slug_continent_of_origin');
			$table->index('slug_country_of_origin');
			$table->index('slug_city_of_origin');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('data', function($table){
			$table->dropIndex('data_search');
			$table->dropIndex('data_slug_continent_of_destination_index');
			$table->dropIndex('data_slug_country_of_destination_index');
			$table->dropIndex('data_slug_region_of_destination_index');
			$table->dropIndex('data_slug_city_of_destination_index');
			$table->dropIndex('data_slug_continent_of_origin_index');
			$table->dropIndex('data_slug_country_of_origin_index');
			$table->dropIndex('data_slug_city_of_origin_index');
		});
	}

}
