<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveProgram extends Model {

	protected $fillable = [
		'program_id',
		'status',
		'custom_categorie'
	];

	public function program()
	{
		return $this->hasOne(Program::class, 'program_id', 'program_id');
	}

}