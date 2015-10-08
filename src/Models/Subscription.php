<?php
namespace Bahjaat\Daisycon\Models;
use Illuminate\Database\Eloquent\Model as Eloquent;

// namespace GrahamCampbell\Database\Models\Interfaces;


class Subscription extends \Eloquent {
	protected $fillable = ['program_id', 'advertiser_id', 'media'];
}