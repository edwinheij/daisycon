<?php

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Models\Country;

class ImportCountries
{
    public function run()
    {
        $jsonPath = \App::basePath() . '/vendor/umpirsky/country-list/data/nl_NL/country.json';
        collect(json_decode(file_get_contents($jsonPath)))->map(function ($country, $short) {
            Country::updateOrCreate([
                'short' => $short
            ], compact('short', 'country'));
        });

        $this->addMissingCountries();
    }

    protected function addMissingCountries()
    {
        $missingCountries = config('daisycon.missing_countries');

        foreach ($missingCountries as $short => $country) {
            Country::updateOrCreate([
                'short' => $short
            ], compact('short', 'country'));
        }
    }
}
