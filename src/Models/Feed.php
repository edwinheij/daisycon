<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Feed extends \Eloquent {

	protected $fillable = [
		'feed_id',
		'name',
		'program_id',
		'product_count',
		'last_update',
		'feed_link_csv',
		'feed_link_xml',
		'feed_link_xmlatt',
		'feed_link_csv_update',
		'feed_link_xml_update',
		'feed_link_xmlatt_update',
		'subscribed'
	];

}

// php artisan generate:migration add_fields_to_feeds_table --fields="feed_link_csv:string:nullable, feed_link_xml:string:nullable, feed_link_xmlatt:string:nullable, feed_link_csv_update:string:nullable, feed_link_xml_update:string:nullable, feed_link_xmlatt_update:string:nullable, subscribed:string:nullable" --path="workbench\bahjaat\daisycon\src\database\migrations"
// php artisan generate:migration remove_feed_link_from_feeds_table --fields="feed_link:string:nullable" --path="workbench\bahjaat\daisycon\src\database\migrations"
// php artisan migrate --path="workbench/bahjaat/daisycon/src/database/migrations/"