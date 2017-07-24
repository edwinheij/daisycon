<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCurrencyIdFieldToFeedsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feeds', function(Blueprint $table)
        {
            $table->integer('currency_id')->unsigned()->after('standard_id');
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
            $table->dropColumn('currency_id');
        });
    }

}
