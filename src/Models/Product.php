<?php

namespace Bahjaat\Daisycon\Models;

use Bahjaat\Daisycon\Repository\ProductFixers\ProductFixer;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use ProductMutators, Sluggable;

    protected $guarded = [];

    protected $fillable = [
        'productfeed_id',
        'description', 'sku', 'link', 'price', 'price_old', 'title',
        'accommodation_name', 'accommodation_type',
        'duration_days', 'duration_nights',
        'destination_city', 'destination_city_link',
        'airport_departure',
        'departure_date', 'destination_region', 'destination_country',
        'travel_trip_type', 'star_rating', 'image',
    ];

    protected $dates = [
        'departure_date'
    ];

    protected $with = ['productinfo'];

    protected $casts = [
//        'price' => 'double'
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
