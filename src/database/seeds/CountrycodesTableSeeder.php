<?php

use Bahjaat\Daisycon\Repository\ImportCountries;
use Illuminate\Database\Seeder;

class CountrycodesTableSeeder extends Seeder
{

    public function run()
    {
        $country = new ImportCountries();
        $country->run();
    }

}