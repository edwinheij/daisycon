<?php

namespace Bahjaat\Daisycon\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;
use Bahjaat\Daisycon\Helper\DaisyconHelper;

use Bahjaat\Daisycon\Models\Program as Program;

class DaisyconPrograms extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'daisycon:getprograms';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Alle programma\'s importeren en opslaan in de database.';

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

		$page = 1;
		$per_page = 50;
		$notLastPage = true;

		while ($notLastPage) {


			$options = array(
				'productfeed' => 'true',
				'page' => $page,
				'per_page' => $per_page,
			);
			$APIdata = DaisyconHelper::getRestAPI("programs", $options);

			if (is_array($APIdata)) {
				$resultCount = count($APIdata['response']);
				if ($resultCount > 0) {
					if ($page == 1)
					{
						$this->info('Tabel leegmaken');
						Program::truncate();
					}

					foreach ($APIdata['response'] as $program) {
						$program = (array)$program;

						/**
						 * id
						 **/
						$program['program_id'] = $program['id'];
						unset($program['id']);

						/**
						 * description
						 **/
						$program['description'] = $program['descriptions'][0]->description;

						/**
						 * url
						 **/
						$program['url'] = DaisyconHelper::changeProgramURL($program['url']);

						Program::create((array)$program);
					}
				} else {
					$this->comment('Geen programma\'s gevonden');
				}
			}
			if ($resultCount < $per_page) $notLastPage = false;
			$page++;
		} // while
		$count = Program::all()->count();
		return $this->info( $count . ' programma\'s geimporteerd. DONE.');
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
