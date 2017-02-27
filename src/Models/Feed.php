<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{

    /*protected $fillable = [
        'feed_id',
        'name',
        'program_id',
        'product_count',
        'last_update',
        'feed_link_csv',
        'feed_link_xml',
        'feed_link_xmlatt',
        'feed_link_csv_update',
        'feed_link_xml_update',
        'feed_link_xmlatt_update',
    ];*/

    protected $guarded = [];

    protected $casts = [
        'locale_ids' => 'array',
        'subscribed_media_ids' => 'array',
    ];

    public function setLocaleIdsAttribute($localeIds) {
        $this->attributes['locale_ids'] = serialize($localeIds);
    }

    public function setSubscribedMediaIdsAttribute($subscribedMediaIds) {
        $this->attributes['subscribed_media_ids'] = serialize($subscribedMediaIds);
    }

}
