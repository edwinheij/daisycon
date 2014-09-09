<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProgramsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('programs', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');
			$table->integer('program_id')->unsigned();
			$table->integer('advertiser_id')->unsigned();
			$table->string('url')->nullable();;
			$table->string('program_logo')->nullable();;
			$table->string('name')->nullable();;
			$table->string('description')->nullable();;
			$table->string('productfeed')->nullable();;
			$table->string('subscribed_media_ids')->nullable();;
			$table->string('category_name')->nullable();;
			$table->string('status')->nullable();;
			$table->date('startdate')->nullable();;
			$table->date('enddate')->nullable();;
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
		Schema::drop('programs');
	}

}
