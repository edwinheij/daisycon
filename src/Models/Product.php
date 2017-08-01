<?php

namespace Bahjaat\Daisycon\Models;

use Bahjaat\Daisycon\Repository\ProductFixers\ProductFixer;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

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
        'has_airco'          => 'bool',
        'has_barbecue'       => 'bool',
        'has_child_chair'    => 'bool',
        'has_dishwasher'     => 'bool',
        'has_electricity'    => 'bool',
        'has_garage'         => 'bool',
        'has_garden'         => 'bool',
        'has_heating'        => 'bool',
        'has_internet'       => 'bool',
        'has_livingroom'     => 'bool',
        'has_microwave'      => 'bool',
        'has_playground'     => 'bool',
        'has_sauna'          => 'bool',
        'has_swimmingpool'   => 'bool',
        'has_telephone'      => 'bool',
        'has_television'     => 'bool',
        'has_washingmachine' => 'bool',
        'in_stock'           => 'bool'
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
        return $this->hasOne(Productinfo::class, 'daisycon_unique_id', 'daisycon_unique_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'accommodation_name_slug'  => [
                'source' => 'accommodation_name'
            ],
            'destination_city_slug'    => [
                'source' => 'destination_city',
                'unique' => false
            ],
            'destination_country_slug' => [
                'source' => 'destination_country',
                'unique' => false
            ],
            'destination_region_slug'  => [
                'source' => 'destination_region',
                'unique' => false
            ],
            'title_slug'               => [
                'source' => 'title',
            ],
        ];
    }
}
