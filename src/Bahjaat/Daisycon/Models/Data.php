<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Config;
use Str;

class Data extends \Eloquent {
	protected $fillable = []; // see constructor
	// protected $guarded = ['link'];
	// protected $table = 'data';

	public function __construct(array $attributes = array())
	{
		// $db_fields_to_import = DaisyconHelper::db_fields_to_import();
		// $custom_db_fields_to_import = Config::get('daisycon::config.custom_db_fields_to_import');
		
		$this->fillable( array_merge(DaisyconHelper::getDatabaseFields(), array('program_id', 'feed_id')) );
		parent::__construct($attributes);
	}
	
	public static function boot()
	{
		parent::boot();
		
		static::creating(function($data)
		{
			// dd(DaisyconHelper::getDatabaseFields());
			// dd($data->fillableFromArray());
			// dd($data->getAttributes());
			// foreach ($data->getAttibutes)
			foreach (DaisyconHelper::getDatabaseFields() as $fieldname)
			{
				if (str_contains($fieldname, 'slug_'))
				{
					$originalFieldName = str_replace('slug_','',$fieldname);
					if (! empty($data->$originalFieldName)) $data->$fieldname = Str::slug($data->$originalFieldName);
				}
			}
			//dd();
			// $data->slug_accommodation_name = \Str::slug($data->accommodation_name);
		});
		
	}
}