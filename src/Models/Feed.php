<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{

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
