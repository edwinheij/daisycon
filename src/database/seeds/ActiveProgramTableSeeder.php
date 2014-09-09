<?php

// Composer: "fzaninotto/faker": "v1.3.0"
// use Faker\Factory as Faker;

use Bahjaat\Daisycon\Models\ActiveProgram;

class ActiveProgramTableSeeder extends Seeder {

	public function run()
	{
		// $faker = Faker::create();

		ActiveProgram::truncate();

		$programs = [ 192, 470 ];

		foreach($programs as $program)
		{
			ActiveProgram::create([
				'program_id' => $program,
				'status' => 1
			]);
		}

		$this->command->info('ActiveProgram table seeded!');
	}

}