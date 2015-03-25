<?php namespace Bahjaat\Daisycon\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;
use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Bahjaat\Daisycon\Models\Data;
use Illuminate\Support\Str;

class DaisyconFixData extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'daisycon:fix-data';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Data tabel proberen zo netjes mogelijk weg te zetten';

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
		$this->info('Alle records aanvullen met gegevens welke ontbreken vanuit CSV-bestand');

		$aantal = true;
		while ($aantal != 0) {
			$rij = Data::select('id')->where('temp', '!=', 2)->take(300)->get();
			$aantal = $rij->count();
			if ($aantal > 0) {
				foreach ($rij as $ro) {
					$r = Data::find($ro->id);
					$r->temp = 2;
					$r->save();
				}
			}
			$this->info($aantal);
		}

		$this->info('\'region_of_destination\' fixen...');
		$regionLeeg = Data::select('city_of_destination', 'region_of_destination')
			->where('region_of_destination', '')
			->groupBy('city_of_destination')
			->get();
		if ($regionLeeg->count() > 0) {
			$city = Data::select('city_of_destination', 'region_of_destination')
				->where('region_of_destination', '!=', '')
				->whereIn('city_of_destination', array_fetch($regionLeeg->toArray(), 'city_of_destination'))
				->groupBy('city_of_destination')
				->get();

			foreach ($city->toArray() as $row)
			{
				Data::where('city_of_destination', $row['city_of_destination'])
					->where('region_of_destination', '')
					->update(
						array(
							'region_of_destination' => $row['region_of_destination'],
							'slug_region_of_destination' => Str::slug($row['region_of_destination'])
						)
					);
			}
		}

		/**
		 * Zijn er na bovenstaande actie nog steeds regels over zonder 'region_of_destination' dan verwijderen
		 */
		$rowsStillNotOK = $regionLeeg2 = Data::where('region_of_destination', '')->delete();
		if ($rowsStillNotOK > 0)
		{
			$this->info('\'region_of_destination\' verwijderd: '. $rowsStillNotOK .' accommodaties');
		}

		$this->info('\'region_of_destination\' fixen... DONE');

		/**
		 * Empty slug fixing
		 */

		/*$this->info('\'slug_region_of_destination\' fixen...');

		$slugToFill = Data::select('id', 'region_of_destination', 'slug_region_of_destination')
			->where('region_of_destination', '!=', '')
			->where('slug_region_of_destination', '')
			->get();

		if ($slugToFill->count() > 0)
		{
			foreach ($slugToFill->toArray() as $row)
			{
				Data::where('id', $row['id'])->update(
					array(
						'slug_region_of_destination' => \Str::slug($row['region_of_destination'])
					)
				);
			}
		}
		$this->info('\'slug_region_of_destination\' fixen... DONE');*/
		$this->call('cache:clear');
		return $this->info('Done');
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
