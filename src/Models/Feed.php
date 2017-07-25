<?php

namespace Bahjaat\Daisycon\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser\StringWalker;
use Prewk\XmlStringStreamer\Stream\Guzzle;

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

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function getUrlAttribute($url)
    {
//        print_r($url);
//        print_r(config('daisycon.media_id'));
        print_r($this->subscribed_media_ids);
        dd();
        if ( ! in_array($media_id = config('daisycon.media_id'), $this->subscribed_media_ids)) {
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



}
