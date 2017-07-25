<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductfeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productfeeds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('program_id')->nullable(); //->unsigned();
            $table->unsignedInteger('standard_id')->nullable(); //->unsigned();
            $table->unsignedInteger('currency_id')->nullable(); //->unsigned();
            $table->string('language_code')->nullable();
            $table->unsignedInteger('products')->nullable(); //->unsigned();
            $table->text('locale_ids')->nullable(); //->unsigned();
            $table->text('subscribed_media_ids')->nullable(); //->unsigned();
            $table->string('url')->nullable();
            $table->unsignedInteger('dedicated_media_id')->nullable(); //->unsigned();

            // data from productfeed
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->dateTime('last_modified')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->dateTime('previous_download')->nullable();

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
        Schema::dropIfExists('productfeeds');
    }
}
