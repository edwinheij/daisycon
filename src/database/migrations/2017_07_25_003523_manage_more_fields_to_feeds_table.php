<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ManageMoreFieldsToFeedsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feeds', function(Blueprint $table)
        {
            $table->string('locale_ids')->after('products');
            $table->string('subscribed_media_ids')->after('locale_ids');
            $table->string('url')->after('subscribed_media_ids');
            $table->unsignedInteger('dedicated_media_id')->nullable()->after('url');
            $table->integer('product_count')->nullable()->change();

            $table->dropColumn('name');

            $table->dropColumn('feed_link_csv');
            $table->dropColumn('feed_link_xml');
            $table->dropColumn('feed_link_xmlatt');

            $table->dropColumn('feed_link_csv_update');
            $table->dropColumn('feed_link_xml_update');
            $table->dropColumn('feed_link_xmlatt_update');

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
            $table->dropColumn('locale_ids');
            $table->dropColumn('subscribed_media_ids');
            $table->dropColumn('url');
            $table->dropColumn('dedicated_media_id');
            $table->dropColumn('product_count');

        });
    }

}
