<?php

namespace App;

use Bahjaat\Daisycon\Models\LeadRequirement;
use Illuminate\Database\Eloquent\Model;

class LeadAnswer extends Model
{
    protected $guarded = [];

    protected function leadrequirement()
    {
        return $this->belongsTo(LeadRequirement::class);
    }
}
