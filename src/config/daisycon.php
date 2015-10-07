<?php

return array(

    'username' => '', // can also / should be your 'emailaddress'

    'password' => '',

    'media_id' => 0,

	'sub_id' => '',

    'publisher_id' => 0, // https://services.daisycon.com/publishers

    'encoding' => 'UTF-8', // ISO-8859-1 / ISO-8859-15 / UTF-8 / UTF-16 / ASCII

	'html_toestaan' => true, // (bool) true / false

	'feed_type' => 'csv', // 'xml en csv' momenteel actief; later eventueel toe te voegen: xmlatt

	'chunksize' => 500, // alleen van toepassing als bij 'feed_type' 'csv' geselecteerd is

    'db_fields_to_import' => array(

		// Accommodatie
		'title', 
		'link',
		'description',
		'accommodation_name',
		'accommodation_type',
		'min_nr_people',
		'location_description',
		'stars',

		// Prijs
		'minimum_price',
		'maximum_price',
		'lowest_price',

		// Locaties

		// Destination
		'continent_of_destination',
		'country_of_destination',
		'country_link',
		'region_of_destination',
		'region_link',
		'city_of_destination',
		'city_link',
		'longitude',
		'latitude',

		// Origin
		'continent_of_origin',
		'country_of_origin',
		'city_of_origin',
		'port_of_departure',

		// Images
		'img_small',
		'img_medium',
		'img_large',

		// Reis
		'board_type',
		'tour_operator',
		'transportation_type',
		'departure-date', # to fix (see also 'migration')
		'departure_date', # to fix (see also 'migration')
		'end_date', # to fix (see also 'migration')
		'duration',

		// Daisycon internal
		'daisycon_unique_id',
		'internal_id',
		'unique_integer',
		'update_hash'

	),

	'custom_db_fields_to_import' => array(
		'slug_accommodation_name',
		'slug_continent_of_destination',
		'slug_country_of_destination',
		'slug_region_of_destination',
		'slug_city_of_destination',
		'slug_continent_of_origin',
		'slug_country_of_origin',
		'slug_city_of_origin'
	)

);
