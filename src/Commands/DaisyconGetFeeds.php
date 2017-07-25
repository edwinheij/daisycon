<?php

namespace Bahjaat\Daisycon\Commands;

use Bahjaat\Daisycon\Models\Productfeed;
use Bahjaat\Daisycon\Repository\Daisycon;
use Config;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Models\Feed as Feed;
use Bahjaat\Daisycon\Helper\DaisyconHelper;

class DaisyconGetFeeds extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:get-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all feeds into the database.';

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
        $this->info('Truncate productfeed table');
        Productfeed::truncate();

        $this->info('Alle productfeeds ophalen. Dit kan even duren...');

        $this->daisycon
            ->allPages(false)
            ->getProductfeeds();

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
