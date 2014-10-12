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

	// protected $data;

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
			$data = parent::fixTransportationType($data);
			$data = parent::fixBoardingType($data);
			$data = parent::fixLandcodes($data);
			
		});
		
	}

	public function fixTransportationType($data)
	{
        $transpArr = array(
            'EV'   			=> 'Eigen vervoer',
            'BU'    		=> 'Bus',
            'VL'   			=> 'Vliegtuig',
            'flight'		=> 'Vliegtuig',
            'own'   		=> 'Eigen vervoer',
            'eigen vervoer' => 'Eigen vervoer'
        );
        // dd($data);
        if (isset($data->transportation_type))
        {
            if (array_key_exists($data->transportation_type, $transpArr))
            {
                $data->transportation_type = $transpArr[$data->transportation_type];
            }
        }
        return $data;
	}

	public function fixBoardingType($data)
	{
        $boardArr = array(
            'LG'    => 'Logies',
            'LO'    => 'Logies en ontbijt',
            'AI'    => 'All inclusive',
            'HP'    => 'Halfpension',
            'VP'    => 'Volpension'
        );
        if (isset($data->board_type))
        {
            if (array_key_exists($data->board_type, $boardArr))
            {
                $data->board_type = $boardArr[$data->board_type];
            }
        }
        $data->board_type = ucfirst($data->board_type); // logies >> Logies
        return $data;
	}

	public function fixLandcodes($data)
	{
        // $lc = $this->landcodes;
        $fields = array(
            'country_of_destination',
            'country_of_origin'
        );

        foreach ($fields as $field)
        {
            if (isset($data->$field) && strlen($data->$field) == 2)
            {
            	$cc = Countrycode::where('countrycode', $data->$field)->remember(60)->firstOrFail();
                if (! empty($cc->country)) $data->$field = $cc->country;
            }
        }
        return $data;
	}
}