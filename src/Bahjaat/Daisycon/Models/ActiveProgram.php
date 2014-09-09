<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class ActiveProgram extends \Eloquent {
	protected $fillable = 
	[
		'program_id',
		'status'
	];

	public function program()
	{
		return $this->hasOne('Bahjaat\Daisycon\Models\Program', 'program_id', 'program_id');
	}
}