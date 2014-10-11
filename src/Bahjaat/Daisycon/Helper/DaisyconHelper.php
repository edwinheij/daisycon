<?php

namespace Bahjaat\Daisycon\Helper;

use Config;

class DaisyconHelper {
	
	static function getApiOptions()
	{
        $options = array (
	        'login'        => Config::get("daisycon::config.username"), // 'info@service4pc.nl',
	        'password'     => md5(Config::get("daisycon::config.password")), //'3bc328865eced6e4f926da3bba03b811',
	        'features'     => SOAP_SINGLE_ELEMENT_ARRAYS, 
	        'encoding'     => 'UTF-8',
	        'trace'        => 1,
	        'cache_wsdl'   => WSDL_CACHE_NONE // WSDL_CACHE_DISK / WSDL_CACHE_NONE
	    );
	    return $options;
	}

	static function getDatabaseFields()
	{
		return array_merge(
			Config::get('daisycon::config.db_fields_to_import'),
			Config::get('daisycon::config.custom_db_fields_to_import')
		);
	}
}
