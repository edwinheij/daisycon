<?php

namespace Bahjaat\Daisycon\Commands;

use Artisan;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Repository\Daisycon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Bahjaat\Daisycon\Models\Subscription as Subscription;

class DaisyconGetSubscriptions extends Command {

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'daisycon:get-subscriptions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import subscriptions into the database.';

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
        Subscription::truncate();

        $this->info('Alle subscriptions ophalen. Dit kan even duren...');

        $this->daisycon
            ->allPages(false)
            ->getSubscriptions();

        if (Program::count()) {
            $this->info('Creating relations with programs');
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
