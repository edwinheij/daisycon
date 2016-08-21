<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFieldToDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('data', function(Blueprint $table)
		{
            $table->string('custom_category')->nullable()->after('update_hash');
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('data', function(Blueprint $table)
		{
			$table->dropColumn('custom_category');
		});
	}

}
