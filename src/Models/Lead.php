<?php

namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

}
