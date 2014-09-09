<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Feed extends \Eloquent {
	protected $fillable = ['feed_id', 'name', 'program_id', 'product_count', 'last_update', 'feed_link'];

	// public function activeProgram()
	// {
	// 	return $this->belongsTo('Bahjaat\Daisycon\Models\ActiveProgram', 'program_id', 'program_id');
	// }

}