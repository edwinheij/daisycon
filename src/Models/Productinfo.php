<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Productinfo
 */
class Productinfo extends Model
{
    protected $table = 'productinfo';

    protected $guarded = [];

    protected $with = [
//        'product' // no recusion
    ];

    protected $dates = [
        'insert_date', 'update_date', 'delete_date'
    ];

    public function setInsertDateAttribute($value) {
        $this->attributes['insert_date'] = empty($value) ? null : $value;
    }

    public function setUpdateDateAttribute($value) {
        $this->attributes['update_date'] = empty($value) ? null : $value;
    }

    public function setDeleteDateAttribute($value) {
        $this->attributes['delete_date'] = empty($value) ? null : $value;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
