<?php

// Composer: "fzaninotto/faker": "v1.3.0"
// use Faker\Factory as Faker;
use Bahjaat\Daisycon\Models\Countrycode;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer\Stream;

class CountrycodesTableSeeder extends Seeder {

	public function run()
	{

		Countrycode::truncate();

		$CHUNK_SIZE = 1024;
		$streamProvider = new Stream\File(dirname(__FILE__) . "/countrycodes.xml", $CHUNK_SIZE);

		$config = array(
		    "uniqueNode" => "row"
		);

		$parser = new Parser\UniqueNode($config);
        $streamer = new XmlStringStreamer($parser, $streamProvider);

		while ($node = $streamer->getNode()) {
		    $simpleXmlNode = simplexml_load_string($node);
			Countrycode::create([
				'countrycode' => $simpleXmlNode->field[0],
				'country' => $simpleXmlNode->field[1]
			]);
		}

		$this->command->info('Countrycode table seeded!');
	}

}