<?php

namespace Bahjaat\Daisycon\Http\Controllers;


use Bahjaat\Daisycon\Models\ActiveProgram;
use Bahjaat\Daisycon\Models\Program;

class ActiveProgramsController
{
    public function index()
    {
//        $activePrograms = ActiveProgram::with('program.feeds')->get();
        $prg = Program::has('feeds')->with('feeds')->get();
//        dd($activePrograms);
//        dd($prg);

        echo '<pre>';
        $prg->map(function($p) {
            $show = [$p->name,$p->program_id];
            echo implode(' ', $show) . PHP_EOL;
            foreach ($p->feeds as $f) {
                echo '- ' . $f->program_id . ' ' . $f->products . PHP_EOL;
             }
        });
    }
}