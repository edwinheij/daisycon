<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldsToFeedsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('feeds', function(Blueprint $table)
		{
			$table->string('subscribed')->nullable()->after('product_count');
			$table->string('feed_link_xmlatt_update')->nullable()->after('product_count');
			$table->string('feed_link_xml_update')->nullable()->after('product_count');
			$table->string('feed_link_csv_update')->nullable()->after('product_count');
			$table->string('feed_link_xmlatt')->nullable()->after('product_count');
			$table->string('feed_link_xml')->nullable()->after('product_count');
			$table->string('feed_link_csv')->nullable()->after('product_count');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('feeds', function(Blueprint $table)
		{
			$table->dropColumn('feed_link_csv');
			$table->dropColumn('feed_link_xml');
			$table->dropColumn('feed_link_xmlatt');
			$table->dropColumn('feed_link_csv_update');
			$table->dropColumn('feed_link_xml_update');
			$table->dropColumn('feed_link_xmlatt_update');
			$table->dropColumn('subscribed');
		});
	}

}
