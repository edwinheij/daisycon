<?php

use Bahjaat\Daisycon\Models\Country;
use Bahjaat\Daisycon\Repository\ImportCountries;
use Illuminate\Database\Seeder;

class CountrycodesTableSeeder extends Seeder {

	public function run()
	{
		Country::truncate();

        $country = new ImportCountries();
        $country->run();
	}

}