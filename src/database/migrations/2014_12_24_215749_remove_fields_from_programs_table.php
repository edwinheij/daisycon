<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveFieldsFromProgramsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('programs', function(Blueprint $table)
		{
			$table->dropColumn('program_logo');
			$table->dropColumn('subscribed_media_ids');
			$table->dropColumn('category_name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('programs', function(Blueprint $table)
		{
			$table->string('program_logo')->nullable();
			$table->string('subscribed_media_ids')->nullable();
			$table->string('category_name')->nullable();
		});
	}

}
