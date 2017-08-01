<?php

namespace Bahjaat\Daisycon\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function scopeShort($query, $value)
    {
        return $query->where('short', $value);
    }
}
