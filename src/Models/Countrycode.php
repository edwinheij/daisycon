<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class Countrycode extends Model {
	protected $fillable = [
		'countrycode',
		'country'
	];

	public $timestamps = false;
}