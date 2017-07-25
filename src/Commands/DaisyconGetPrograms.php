<?php

namespace Bahjaat\Daisycon\Commands;

use Artisan;
use Bahjaat\Daisycon\Models\Subscription;
use Bahjaat\Daisycon\Repository\Daisycon;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Bahjaat\Daisycon\Models\Program as Program;

class DaisyconGetPrograms extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:get-programs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import programs into the database.';

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
        $this->info('Database tabel leeghalen');
        Program::truncate();

        $this->info('Alle programma\'s ophalen. Dit kan even duren...');

        $this->daisycon
//            ->allPages(false)
            ->getPrograms();

        if (count(Subscription::all())) {
            $this->info('Relaties met subscriptions maken');
            Artisan::call('daisycon:relations');
        }

        $this->info('Klaar');

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
