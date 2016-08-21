<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTempFieldToDataTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data', function(Blueprint $table)
        {
            $table->string('temp')->nullable()->after('custom_category');
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
            $table->dropColumn('temp');
        });
    }

}
