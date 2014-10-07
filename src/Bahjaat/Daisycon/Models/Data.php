<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Bahjaat\Daisycon\Helper\DaisyconHelper;

class Data extends \Eloquent {
	protected $fillable = []; // see constructor
	// protected $guarded = ['link'];
	// protected $table = 'data';

	public function __construct(array $attributes = array())
	{
		$db_fields_to_import = DaisyconHelper::db_fields_to_import();

		$this->fillable( array_merge($db_fields_to_import, array('program_id', 'feed_id', 'slug_accommodation_name')) );
		parent::__construct($attributes);
	}
	
	public static function boot()
	{
		parent::boot();
		
		static::creating(function($data)
		{
			//dd($data);
			$data->slug_accommodation_name = \Str::slug($data->accommodation_name);
		});
		
	}
}