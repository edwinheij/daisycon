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
		$APIdata = DaisyconHelper::getRestAPI("productfeeds");

		if (is_array($APIdata))
		{
			dd($APIdata);
		}

//        $this->info(PHP_EOL);
        return $this->info('Klaar');


	    $sWsdl_program = "http://api.daisycon.com/publisher/soap/program/wsdl/";
        $oSoapClient_program = new \SoapClient($sWsdl_program, DaisyconHelper::getApiOptions());

        $aFilter = array(
        	'subscribed' => 'true'
        );
        if (!empty($aFilter)) $this->comment('Let op, er is een filter actief bij het binnenhalen van de programma\'s: ' . json_encode($aFilter));
        try
        {
            $mResult = $oSoapClient_program->getPrograms($aFilter);
        }
        catch(Exception $e)
        {
            return $this->error('Fout met binnenhalen van de programma\'s');
        }
        if (count($mResult['return']) > 0)
        {
        	$this->info('Tabel leegmaken');
        	Program::truncate();

	        foreach ($mResult['return'] as $program)
	        {
	            Program::create((array) $program);
	        }
	    }
	    else
	    {
	    	$this->info('Geen programma\'s gevonden');
	    }

	    return $this->info('Klaar');
        

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
