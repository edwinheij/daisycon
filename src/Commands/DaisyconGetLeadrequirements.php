<?php

namespace Bahjaat\Daisycon\Commands;

use Config;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Repository\Daisycon;

class DaisyconGetLeadrequirements extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:get-leadrequirements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leadrequirements importeren.';

    protected $daisycon;

    /**
     * Create a new command instance.
     *
     * @param \Bahjaat\Daisycon\Repository\Daisycon $daisycon
     */
    public function __construct(Daisycon $daisycon)
    {
        parent::__construct();
        $this->daisycon = $daisycon;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Leadrequirements importeren');

        $data = [

        ];

        $this->daisycon
            ->getLeadrequirements($data);

        $this->info('Ready');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
