<?php

namespace Bahjaat\Daisycon\Models;

/**
 * Class ProductMutators
 * Let op: Pas ook de test aan als er mutators toegevoegd worden
 *
 * @package App
 */
trait ProductMutators
{
    public function setDescriptionAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['description'] = null;
        }
    }

    public function setSkuAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['sku'] = null;
        }
    }

    public function setLinkAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['link'] = null;
        }
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = empty($value) ? null : (double)$value;
    }

    public function setPriceOldAttribute($value)
    {
        $this->attributes['price_old'] = empty($value) ? null : $value;
    }

    public function setTitleAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['title'] = null;
        }
    }

    public function setAccommodationNameAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['accommodation_name'] = null;
        }
    }

    public function setAccommodationTypeAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['accommodation_type'] = null;
        }
    }

    public function setDurationDaysAttribute($value)
    {
        $this->attributes['duration_days'] = empty($value) ? null : $value;
    }

    public function setDurationNightsAttribute($value)
    {
        $this->attributes['duration_nights'] = empty($value) ? null : $value;
    }

    public function setDstinationCityAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['destination_city'] = null;
        }
    }

    public function setDepartureDateAttribute($value)
    {
        $this->attributes['departure_date'] = empty($value) ? null : $value;
    }

    public function setDestinationCityLinkAttribute($value)
    {
        $this->attributes['destination_city_link'] = empty($value) ? null : $value;
    }

    public function setAirportDepartureAttribute($value)
    {
        $this->attributes['airport_departure'] = empty($value) ? null : $value;
    }

    public function setTravelTripTypeAttribute($value)
    {
        $this->attributes['travel_trip_type'] = empty($value) ? null : $value;
    }

    public function setDestinationRegionAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['destination_region'] = null;
        }
    }

    public function setDestinationCountryAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['destination_country'] = null;
        }
    }

    public function setTripTravelTypeAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['travel_trip_type'] = null;
        }
    }

    public function setStarRatingAttribute($value)
    {
        $this->attributes['star_rating'] = empty($value) ? null : $value;
    }

    public function setImageAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['image'] = null;
        }
    }
}