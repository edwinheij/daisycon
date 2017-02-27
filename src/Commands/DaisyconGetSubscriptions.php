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
		$this->info('Start importing subscriptions');

        // if ($path = $this->option($option))
        // {
        //     return $path;
        // }

        // return Config::get("generators.{$configName}");
        // return Config::get("generators.{$configName}");

        // echo 'Config media_id:  '. Config::get("daisycon");
        // $media_id = Config::get("daisycon.media_id");

	    $sWsdl_program = "http://api.daisycon.com/publisher/soap/program/wsdl/";
        $oSoapClient_program = new \SoapClient($sWsdl_program, DaisyconHelper::getApiOptions());

        $aFilter = array();
        try
        {
            $mResult = $oSoapClient_program->getSubscriptions($aFilter);
        }
        catch(Exception $e)
        {
            // var_dump( $oSoapClient_program->__getLastRequestHeaders() );
            // var_dump( $oSoapClient_program->__getLastResponse() );
            return $this->error('Fout met binnenhalen van de \'subscriptions\'');
        }
        // print_r($mResult);
        // return;

        if (count($mResult['return']) > 0)
        {
        	// Truncate table
			$this->info('Verwijderen van bestaande subscriptions');
	    	Subscription::truncate();

	        foreach ($mResult['return'] as $subscription)
	        {
	        	$subscriptionArray = (array) $subscription;
	        	$subscriptionArray['media'] = json_encode($subscriptionArray['media']);
	        	// dd($subscriptionArray);
	            Subscription::create((array) $subscriptionArray);
	        } // foreach mResult
	    }
	    else
	    {
	    	$this->info('Geen subscriptions gevonden');
	    }
	    return $this->info('done');
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
