<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Productinfo
 */
class Productinfo extends Model
{
    protected $table = 'productinfo';

    protected $with = [
//        'product' // no recusion
    ];

    protected $guarded = [];

    protected $dates = [
        'insert_date', 'update_date', 'delete_date', 'last_modified'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'daisycon_unique_id', 'daisycon_unique_id');
    }
}
