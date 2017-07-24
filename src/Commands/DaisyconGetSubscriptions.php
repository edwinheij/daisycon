<?php

namespace Bahjaat\Daisycon\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;

use Bahjaat\Daisycon\Helper\DaisyconHelper;

// use Prewk\XmlStringStreamer;
// use Prewk\XmlStringStreamer\Stream; 
// use Prewk\XmlStringStreamer\Parser;

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

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
    public function handle()
    {
        $page = 1;
        $per_page = 50;
        $notLastPage = true;

        $options = array(
            'page' => $page,
            'per_page' => $per_page,
            'productfeed' => 'true',
        );

        $this->info('Start importing subscriptions');

        while ($notLastPage) {

            $APIdata = DaisyconHelper::getRestAPI("subscriptions", $options);

            if (is_array($APIdata)) {

                $resultCount = count($APIdata['response']);

                if ($resultCount > 0) {

                    if ($page == 1) {
                        $this->info('Clear subscriptions database table');
                        Subscription::truncate();
                    }

                    foreach ($APIdata['response'] as $subscription) {
                        $subscription = (array)$subscription;

                        foreach ($subscription['program_ids'] as $program_id) {
                            $subscription['program_id']  = $program_id;
                            unset($subscription['program_ids']);
                            Subscription::create((array)$subscription);
                        }

                    }

                    $totalCount = Subscription::all()->count();

                    $comment = sprintf('Page %d loaded with %d record(s); Total records: %d', $page, $resultCount, $totalCount);
                    $this->comment($comment);

                } else {
                    $this->comment('No subscriptions found');
                }
            }

            if ($resultCount < $per_page) $notLastPage = false;
            $options['page'] = $page++;
        }

        $count = Subscription::all()->count();
        return $this->info($count . ' subscriptions imported');

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
