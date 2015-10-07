<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Countrycode extends \Eloquent {
	protected $fillable = [
		'countrycode',
		'country'
	];

	public $timestamps = false;
}