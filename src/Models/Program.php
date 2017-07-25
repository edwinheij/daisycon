<?php

namespace Bahjaat\Daisycon\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $guarded = [];

    protected $dates = [
        'startdate',
        'enddate',
        'incentive_date',
        'last_modified',
        'daisycon_unique_id_since'
    ];

    protected $casts = [
        'keywordmarketing'            => 'boolean',
        'emailmarketing'              => 'boolean',
        'socialmedia'                 => 'boolean',
        'deeplink'                    => 'boolean',
        'productfeed'                 => 'boolean',
        'category_ids'                => 'array',
        'locale_ids'                  => 'array',
        'supply_locale_ids'           => 'array',
        'descriptions'                => 'array',
        'score'                       => 'array',
        'deeplink_ads'                => 'array',
        'publisher_tag'               => 'boolean',
        'commission'                  => 'array',
        'provides_data'               => 'array',
        'daisycon_unique_id_modified' => 'boolean'
    ];

    protected $with = [
//        'productfeeds', 'productfeeds.products'
    ];

    public function setEnddateAttribute($enddate)
    {
        try {
            Carbon::parse($enddate);
        } catch (\Exception $e) {
            $this->attributes['enddate'] = null;
        }
    }

    public function setKeywordmarketingAttribute($keywordmarketing)
    {
        $this->attributes['keywordmarketing'] = (bool)$keywordmarketing;
    }

    public function setEmailmarketingAttribute($emailmarketing)
    {
        $this->attributes['emailmarketing'] = (bool)$emailmarketing;
    }

    public function setSocialmediaAttribute($socialmedia)
    {
        $this->attributes['socialmedia'] = (bool)$socialmedia;
    }

    public function setDeeplinkAttribute($deeplink)
    {
        $this->attributes['deeplink'] = (bool)$deeplink;
    }

    public function setProductfeedAttribute($productfeed)
    {
        $this->attributes['productfeed'] = (bool)$productfeed;
    }

    public function setPublisherTagAttribute($publisher_tag)
    {
        $this->attributes['publisher_tag'] = (bool)$publisher_tag;
    }

    public function setDaisyconUniqueIdModifiedAttribute($value)
    {
        $this->attributes['daisycon_unique_id_modified'] = (bool)$value;
    }

    // Accessors & Mutators

    public function getUrlAttribute($url)
    {
        return str_replace(
            ['#MEDIA_ID#', '&ws=#SUB_ID#'],
            [config('daisycon.media_id', '')],
            $url
        );
    }

    public function getDescriptionAttribute() {
        $value = $this->descriptions;
        $nl = collect($value)->where('language_id', 2)->first();
        return is_null($nl) ? null : $nl['description'];
    }

    /**
     * Relations
     */

    public function productfeeds()
    {
        return $this->hasMany(Feed::class);
    }

    public function subscription()
    {
        return $this->belongsToMany(Subscription::class);
    }

    // public function activeProgram()
    //    {
    //        return $this->belongsTo('Bahjaat\Daisycon\Models\ActiveProgram', 'program_id', 'program_id');
    //    }

//    protected $casts = [
//        'productfeed' => 'boolean'
//    ];

//    public function feeds()
//    {
//        return $this->hasMany(Feed::class, 'program_id', 'program_id');
//    }

//    public function setEnddateAttribute($enddate)
//    {
//        if ($enddate == "0000-00-00") unset($this->attributes['enddate']);
//    }
//
//    public function setProductfeedAttribute($productfeed)
//    {
//        $this->attributes['productfeed'] = ($productfeed == "true") ? true : false;
//    }

}

// php artisan generate:migration create_programs_table --fields="program_id:integer:unsigned, advertiser_id:integer:unsigned, url:string:nullable, program_logo:string, name:string, description:string, productfeed:string, subscribed_media_ids:string, category_name:string, status:string, startdate:date, enddate:date" --path="workbench\bahjaat\daisycon\src\migrations"
// php artisan generate:migration add_field_to_programs_table --fields="display_url:string:nullable" --path="workbench\bahjaat\daisycon\src\database\migrations"
// php artisan generate:migration remove_fields_from_programs_table --fields="program_logo:string:nullable, subscribed_media_ids:string:nullable, category_name:string:nullable" --path="workbench\bahjaat\daisycon\src\database\migrations"

// php artisan migrate --path="workbench/bahjaat/daisycon/src/database/migrations/"