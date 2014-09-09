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


    // protected $media_id = 56829; // sdv (nog vervangen door configitem)

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
            // var_dump( $oSoapClient_program->__getLastRequestHeaders() );
            // var_dump( $oSoapClient_program->__getLastResponse() );
            return $this->error('Fout met binnenhalen van de programma\'s');
        }
        // print_r($mResult);
        // return;
        if (count($mResult['return']) > 0)
        {
        	$this->info('Tabel leegmaken');
        	Program::truncate();

	        foreach ($mResult['return'] as $program)
	        {
	        	// print_r($subscription);
	            // $subsription = (array) $subscription;
	            // $object['media'] = serialize((array)$object['media']);
	            // $this->getsubscriptions->insert($object);
	            // $this->media_id
	            // if ($this->media_id == $subscription->media[0]->media_id)
	            // {
	            	// $this->info('PID: ' . $subscription->program_id . ' AID: ' . $subscription->advertiser_id ); //. ' media_id: ' . $subscription->media[0]->media_id);
					//$this->info(json_encode($program));

					// foreach (json_decode(json_encode($program)) as $k => $v)
					// {
					// 	// $this->info($k);
					// }
					// return;
	            // }
	            
	            //$subscriptionModel = new Subscription();
	            // unset($subscription->media);
	            // print_r($subscription);
	            // return;
	            // if (stristr($program->name, 'sunweb zomer')) dd($program);
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
