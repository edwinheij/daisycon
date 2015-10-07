<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveFeedLinkFromFeedsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('feeds', function(Blueprint $table)
		{
			$table->dropColumn('feed_link');
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
			$table->string('feed_link')->nullable();
		});
	}

}
