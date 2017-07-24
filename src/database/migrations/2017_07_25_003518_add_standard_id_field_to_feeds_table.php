<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddStandardIdFieldToFeedsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feeds', function(Blueprint $table)
        {
            $table->integer('standard_id')->unsigned()->after('program_id');
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
            $table->dropColumn('standard_id');
        });
    }

}
