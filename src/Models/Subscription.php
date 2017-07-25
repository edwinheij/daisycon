<?php

namespace Bahjaat\Daisycon\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {

    use DateTrait;

    protected $guarded = [];

    protected $dates = [
        'subscribe_date',
        'approval_date',
    ];

    protected $casts = [
        'program_ids' => 'array',
    ];

    protected $with = [
        'programs'
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function scopeOpVolgorde($query)
    {
        return $query->orderByRaw(
            \DB::raw(
                '`status` = \'approved\' desc, ' .
                '`status` = \'open\' desc, ' .
                '`status` = \'canceled\' desc, ' .
                '`status` = \'disapproved\' desc'
            )
        );
    }

    public function setSubscribeDateAttribute($subscribe_date)
    {
        try {
            $this->attributes['subscribe_date'] = Carbon::parse($subscribe_date);
        } catch (\Exception $e) {
            $this->attributes['subscribe_date'] = null;
        }
    }

    public function setApprovalDateAttribute($approval_date)
    {
        try {
            $this->attributes['approval_date'] = Carbon::parse($approval_date);
        } catch (\Exception $e) {
            $this->attributes['approval_date'] = null;
        }
    }

    public function scopeApproved($query) {
        return $query->where('status', 'approved');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }

}