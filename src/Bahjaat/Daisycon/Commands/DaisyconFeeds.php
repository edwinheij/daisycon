<?php

namespace Bahjaat\Daisycon\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream; 
use Prewk\XmlStringStreamer\Parser;

use Bahjaat\Daisycon\Models\Feed as Feed;
use Bahjaat\Daisycon\Models\Subscription as Subscription;
use Bahjaat\Daisycon\Helper\DaisyconHelper;

class DaisyconFeeds extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'daisycon:getfeeds';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Alle feed-url\'s importeren in de database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
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
	public function fire()
	{
		$media_id = Config::get("daisycon::config.media_id");
		$sub_id = Config::get("daisycon::config.sub_id");

		$this->info('Starten met het binnenhalen van de feeds');

		$page = 1;
		$per_page = 500;
		$notLastPage = true;

		while ($notLastPage)
		{
			$options = array(
				'page' => $page,
				'per_page' => $per_page,
				'placeholder_media_id' => $media_id,
				'placeholder_subid' => $sub_id
			);
			$APIdata = DaisyconHelper::getRestAPI("productfeeds", $options);
			if (is_array($APIdata)) {
				$resultCount = count($APIdata['response']);
				if ($resultCount > 0) {
					if ($page == 1)
					{
						$this->info('Verwijderen van bestaande feeds');
						Feed::truncate();
					}
					foreach ($APIdata['response'] as $feedinfo)
					{
						$feedinfo = (array) $feedinfo;

						/**
						 * id
						 **/
						$feedinfo['feed_id'] = $feedinfo['id'];
						unset($feedinfo['id']);

						$feedinfo['subscribed'] = implode(',', $feedinfo['subscribed']);
						if (stristr($feedinfo['subscribed'], (string) $media_id))
						{
							Feed::create( $feedinfo );
						}

					}
				}
				else
				{
					return $this->comment('Geen feeds gevonden');
				}
			}
			if (isset($resultCount) && $resultCount < $per_page) $notLastPage = false;
			$page++;
		} // while
		$count = Feed::all()->count();
		return $this->info( $count . ' feeds geimporteerd. DONE.');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			//array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
