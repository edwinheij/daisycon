<?php

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Models\Program;
use Illuminate\Console\Command;

interface DataImportInterface {

    public function import(Program $program, Command $command);

}