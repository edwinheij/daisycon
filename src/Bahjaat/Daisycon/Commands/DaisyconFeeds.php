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
		$this->info('Starten met het binnenhalen van de feeds');
		$this->info('Verwijderen van bestaande feeds');
		Feed::truncate();

		// $type = 'xml'; // xml, xmlatt, csv
		// $encoding = 'UTF-8'; // 'ISO-8859-15', 'ISO-8859-1', 'UTF-8', 'UTF-16', 'ASCII'
		$aFilter = array(
			'media_id' => Config::get("daisycon::config.media_id"),
			'type' => 'xml', // xml, xmlatt, csv
			'encoding' => Config::get("daisycon::config.encoding"),
			// 'program_id' => $program_id
		);
	    $sWsdl_program = "http://api.daisycon.com/publisher/soap/feed/wsdl/";
        $oSoapClient_program = new \SoapClient($sWsdl_program, DaisyconHelper::getApiOptions());
        try
        {
            $feeds = $oSoapClient_program->getFeeds($aFilter);
        }
        catch(Exception $e)
        {
            // var_dump( $oSoapClient_program->__getLastRequestHeaders() );
            // var_dump( $oSoapClient_program->__getLastResponse() );
            return $this->error('Fout met binnenhalen van de \'feeds\'');
        }
        if (count($feeds['return']) > 0)
        {
	        $this->info('Feeds toevoegen aan database');
	        foreach ($feeds['return'] as $feedinfo)
	        {
	        	Feed::create( (array) $feedinfo );
	        }

	    }
	    else
	    {
	    	$this->comment('Geen feeds gevonden');
	    }
        return $this->info( $feeds['responseInfo']->totalResults . ' feeds geimporteerd');
        
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
