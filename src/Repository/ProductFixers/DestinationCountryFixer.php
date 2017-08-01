<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

use Bahjaat\Daisycon\Models\Country;
use Bahjaat\Daisycon\Models\Product;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestinationCountryFixer implements Fixer
{
    protected $model;

    public function handle($model)
    {
        $this->model = $model;

        $this->replaceWithFullCountry();
    }

    protected function replaceWithFullCountry()
    {
        $short_country = $this->model->destination_country;

        try {
            $country = Country::short($short_country)->firstOrFail();
            $this->model->destination_country = $country->country;

            // Slug it again because it's changed
            $destination_country_slug = SlugService::createSlug(
                Product::class,
                'destination_country_slug',
                $country->country,
                [
                    'unique' => false
                ]
            );

            $this->model->destination_country_slug = $destination_country_slug;

        } catch (ModelNotFoundException $e) {
            \Log::error(sprintf('%s not found in Country table', $short_country));
        }

    }

}