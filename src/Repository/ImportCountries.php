<?php

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Models\Country;

class ImportCountries
{
    public function run()
    {
        $jsonPath = \App::basePath() . '/vendor/umpirsky/country-list/data/nl_NL/country.json';
        collect(json_decode(file_get_contents($jsonPath)))->map(function($country, $short) {
            Country::create(compact('short', 'country'));
        });
    }
}
