<?php

namespace Bahjaat\Daisycon\Models;

use Bahjaat\Daisycon\Repository\ProductFixers\ProductFixer;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ProductMutators, Sluggable;

    protected $guarded = [];

    protected $dates = [
        'accommodation_lowest_date',
        'arrival_date',
        'available_from',
        'departure_date',
        'departure_date_return',
    ];

    protected $with = ['productinfo'];

    protected $casts = [
        'in_stock' => 'bool'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model = $model->fix();
        });
    }

    protected function fix()
    {
        ProductFixer::apply($this);
        return $this;
    }

    public function productinfo()
    {
        return $this->hasOne(Productinfo::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'destination_country', 'short');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'accommodation_name_slug' => [
                'source' => 'accommodation_name'
            ]
        ];
    }
}
