<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscriptions', function(Blueprint $table)
		{
            $table->increments('id');
            $table->dateTime('subscribe_date')->nullable();
            $table->dateTime('approval_date')->nullable();
            $table->string('cpc_status')->nullable();
            $table->string('co_status')->nullable();
            $table->string('status')->nullable();
            $table->text('program_ids')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }

}
