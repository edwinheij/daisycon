<?php

namespace Bahjaat\Daisycon\Models;

use Config;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Productfeed extends Model
{
    protected $guarded = [];

    protected $casts = [
        'locale_ids'           => 'array',
        'subscribed_media_ids' => 'array',
    ];

    protected $dates = [
        'last_modified',
        'date_created',
        'previous_download'
    ];

    protected $with = ['products'];

    protected static function boot()
    {
        parent::boot();

        self::addGlobalScope(function($builder) {
            return $builder->where('language_code', 'nl');
        });
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function getUrlAttribute($url)
    {
        if ( ! in_array($media_id = Config::get('daisycon.media_id'), $this->subscribed_media_ids)) {
            throw new Exception('Media_id is not subscribed to this productfeed');
        }

        if ( ! in_array($locale_id = 1, $this->locale_ids)) {
            throw new Exception('Locale_id (' . $locale_id . ') is not available with this productfeed');
        }

        return str_replace(
            ['#MEDIA_ID#', '#LOCALE_ID#'],
            [$media_id, $locale_id],
            $url
        );
    }

    public function destinationCountry() {
        return $this->belongsTo(Country::class, 'destination_country', 'country');
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

}
