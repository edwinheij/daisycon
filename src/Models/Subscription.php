<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {

    protected $guarded = [];

    public function setSubscribeDateAttribute($value)
    {
        if ($value == '0000-00-00 00:00:00') {
            $this->attributes['subscribe_date'] = null;
        } else {
            $this->attributes['subscribe_date'] = $value;
        }
    }

    public function setApprovalDateAttribute($value)
    {
        if ($value == '0000-00-00 00:00:00') {
            $this->attributes['approval_date'] = null;
        } else {
            $this->attributes['approval_date'] = $value;
        }
    }
}