<?php

namespace Bahjaat\Daisycon\Models;

use Bahjaat\Daisycon\Helper\DaisyconHelper;
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
