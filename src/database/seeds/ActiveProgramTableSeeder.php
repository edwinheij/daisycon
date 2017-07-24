<?php

use Bahjaat\Daisycon\Models\ActiveProgram;
use Illuminate\Database\Seeder;

class ActiveProgramTableSeeder extends Seeder
{

    public function run()
    {
        ActiveProgram::truncate();

        $programs = [
            43 => array('custom_categorie' => 'zomer'),
            110 => array('custom_categorie' => 'zomer'),
            170 => array('custom_categorie' => 'zomer'),
            191 => array('custom_categorie' => 'zomer'),
            192 => array('custom_categorie' => ''),
            387 => array('custom_categorie' => 'eindhoven'),
            388 => array('custom_categorie' => 'maastricht'),
            389 => array('custom_categorie' => 'rotterdam'),
            390 => array('custom_categorie' => 'eelde'),
            470 => array('custom_categorie' => ''),
            694 => array('custom_categorie' => 'zomer'),
            764 => array('custom_categorie' => 'zomer'),
            864 => array('custom_categorie' => 'zomer'),
            1571 => array('custom_categorie' => 'brussel'),
            1572 => array('custom_categorie' => 'dusseldorf'),
            2929 => array('custom_categorie' => 'schiphol'),
            3663 => array('custom_categorie' => 'zomer')
        ];

        foreach ($programs as $program => $attr) {
            ActiveProgram::create([
                'program_id' => $program,
                'status' => 1
            ]);
        }

        $this->command->info('ActiveProgram table seeded!');
    }

}