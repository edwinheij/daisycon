<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ManageFieldsFromSubscriptionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function(Blueprint $table)
        {
            $table->string('media_id')->after('id');
            $table->timestamp('subscribe_date')->nullable()->after('media_id');
            $table->timestamp('approval_date')->nullable()->after('subscribe_date');
            $table->string('cpc_status')->nullable()->after('approval_date');
            $table->string('co_status')->nullable()->after('cpc_status');
            $table->string('status')->nullable()->after('co_status');

            $table->dropColumn('advertiser_id');
            $table->dropColumn('media');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function(Blueprint $table)
        {
            $table->dropColumn('media_id');
            $table->dropColumn('subscribe_date');
            $table->dropColumn('approval_date');
            $table->dropColumn('cpc_status');
            $table->dropColumn('co_status');
            $table->dropColumn('status');

            $table->unsignedInteger('advertiser_id');
            $table->string('media');
        });
    }

}
