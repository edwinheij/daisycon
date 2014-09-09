<?php

namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;


class Program extends \Eloquent {
	protected $fillable = 
	[
		'program_id',
		'advertiser_id',
		'url',
		'program_logo',
		'name',
		'description',
		'productfeed',
		'subscribed_media_ids',
		'category_name',
		'status',
		'startdate',
		'enddate'
	];

	// public function activeProgram()
 //    {
 //        return $this->belongsTo('Bahjaat\Daisycon\Models\ActiveProgram', 'program_id', 'program_id');
 //    }
	public function feeds()
	{
		// return $this->belongsTo('Bahjaat\Daisycon\Models\Feed', 'program_id', 'program_id');
		return $this->hasMany('Bahjaat\Daisycon\Models\Feed', 'program_id', 'program_id');
	}

}

// php artisan generate:migration create_programs_table --fields="program_id:integer:unsigned, advertiser_id:integer:unsigned, url:string:nullable, program_logo:string, name:string, description:string, productfeed:string, subscribed_media_ids:string, category_name:string, status:string, startdate:date, enddate:date" --path="workbench\bahjaat\daisycon\src\migrations"