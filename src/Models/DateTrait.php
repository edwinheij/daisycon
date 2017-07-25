<?php

namespace Bahjaat\Daisycon\Models;

use DateTimeInterface;
use Jenssegers\Date\Date;

trait DateTrait
{
    public function freshTimestamp()
    {
        return new Date();
    }

    protected function asDateTime($value)
    {
        if ($value instanceof Date) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return new Date(
                $value->format('Y-m-d H:i:s.u'), $value->getTimezone()
            );
        }

        if (is_numeric($value)) {
            return Date::createFromTimestamp($value);
        }

        if ($this->isStandardDateFormat($value)) {
            return Date::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        return Date::createFromFormat(
            $this->getDateFormat(), $value
        );
    }
}