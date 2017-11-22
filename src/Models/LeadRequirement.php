<?php

namespace Bahjaat\Daisycon\Models;

use App\LeadAnswer;
use Illuminate\Database\Eloquent\Model;

class LeadRequirement extends Model
{
    protected $guarded = [];

    protected $casts = [
        'required' => 'boolean'
    ];

    protected function answer()
    {
        return $this->hasOne(LeadAnswer::class);
    }

}
