<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldToActiveProgramsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('active_programs', function(Blueprint $table)
		{
			$table->string('custom_category')->nullable()->after('status');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('active_programs', function(Blueprint $table)
		{
			$table->dropColumn('custom_category');
		});
	}

}
