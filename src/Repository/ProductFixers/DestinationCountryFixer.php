<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

use Bahjaat\Daisycon\Models\Country;
use Bahjaat\Daisycon\Models\Product;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestinationCountryFixer implements Fixer
{
    protected $data;

    public function fix($data)
    {
        $this->data = $data;

        $this->replaceWithFullCountry();

        return $this->data;
    }

    protected function replaceWithFullCountry()
    {
        $short_country = $this->data['destination_country'];

        try {
            $country = Country::short($short_country)->firstOrFail();
            $this->data['destination_country'] = $country->country;
        } catch (ModelNotFoundException $e) {
            \Log::error(sprintf('%s not found in Country table', $short_country));
        }

    }

}