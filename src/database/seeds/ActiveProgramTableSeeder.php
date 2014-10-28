<?php

// Composer: "fzaninotto/faker": "v1.3.0"
// use Faker\Factory as Faker;

use Bahjaat\Daisycon\Models\ActiveProgram;

class ActiveProgramTableSeeder extends Seeder {

	public function run()
	{
		// $faker = Faker::create();

		ActiveProgram::truncate();

		$programs = [
			192 => array('custom_categorie' => ''),
			470 => array('custom_categorie' => ''),
			191 => array('custom_categorie' => 'zomer')
		];

		foreach($programs as $program => $attr)
		{
			ActiveProgram::create([
				'program_id' => $program,
				'status' => 1,
				'custom_categorie' => $attr['custom_categorie']
			]);
		}

		$this->command->info('ActiveProgram table seeded!');
	}

}