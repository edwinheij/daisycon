<?php

namespace Bahjaat\Daisycon\Models;

use Bahjaat\Daisycon\Repository\ProductFixers\ProductFixer;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Sluggable;

    protected $guarded = [];

    protected $with = ['productinfo'];

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
