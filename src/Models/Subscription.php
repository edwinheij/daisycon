<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {
	protected $fillable = ['program_id', 'advertiser_id', 'media'];
}