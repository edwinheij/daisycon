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
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('advertiser_id')->nullable();
            $table->string('name')->nullable();
            $table->string('display_url')->nullable();
            $table->date('startdate')->nullable();
            $table->date('enddate')->nullable();
            $table->string('status')->nullable();
            $table->string('program_logo')->nullable();
            $table->string('url')->nullable();
            $table->string('type')->nullable();
            $table->date('incentive_date')->nullable();
            $table->boolean('keywordmarketing')->nullable();
            $table->boolean('emailmarketing')->nullable();
            $table->string('cashback')->nullable();
            $table->boolean('socialmedia')->nullable();
            $table->boolean('deeplink')->nullable();
            $table->boolean('productfeed')->nullable();
            $table->text('category_ids')->nullable();
            $table->text('locale_ids')->nullable();
            $table->text('supply_locale_ids')->nullable();
            $table->longText('descriptions')->nullable();
            $table->longText('description')->nullable();
            $table->string('logo')->nullable();
            $table->text('score')->nullable();
            $table->string('tracking_segment')->nullable();
            $table->text('deeplink_ads')->nullable();
            $table->unsignedInteger('tracking_duration')->nullable();
            $table->boolean('publisher_tag')->nullable();
            $table->text('commission')->nullable();
            $table->text('provides_data')->nullable();
            $table->unsignedInteger('primary_category_id')->nullable();

            // data from productfeed
            $table->string('currency')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->dateTime('last_modified')->nullable();
            $table->dateTime('daisycon_unique_id_since')->nullable();
            $table->boolean('daisycon_unique_id_modified')->nullable();

            $table->timestamps();
        });

        Schema::create('program_subscription', function (Blueprint $table) {
//            $table->increments('id');
            $table->unsignedInteger('program_id')->index();
            $table->unsignedInteger('subscription_id')->index();
//            $table->timestamps();

            $table->primary(['program_id', 'subscription_id']);
        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('programs');
        Schema::dropIfExists('program_subscription');
	}

}
