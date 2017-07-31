<?php

namespace Bahjaat\Daisycon\Repository;

use File;
use League\Csv\Reader;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Models\Product;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Models\Productfeed;

class JsonDataImport implements DataImportInterface
{

    /**
     * Import
     *
     * @param \Bahjaat\Daisycon\Models\Program $program
     * @param \Illuminate\Console\Command      $command
     */
    public function import(Program $program, Command $command)
    {
        $program->productfeeds()->each(function ($feed) use ($command) {
            $this->importfeed($feed, $command);
        });
    }

    /**
     * Import feed
     *
     * @param \Bahjaat\Daisycon\Models\Productfeed $feed
     * @param \Illuminate\Console\Command          $command
     */
    public function importfeed(Productfeed $feed, Command $command)
    {
        $fileLocation = storage_path() . '/' . $feed->program->id . '.' . $feed->id . '.csv';

        $this->downloadAndSaveFeed($feed->url, $fileLocation, $command);

        $offset      = 1; // to skip header
        $batchAantal = 1000;

        $csv = Reader::createFromPath($fileLocation);
        $csv->setDelimiter(';');
        $csv->setEnclosure('"');

        $header = $csv->fetchOne();

        $creationCount = 0;

        while (true) {
            // Flushing the QueryLog before using too much memory
            \DB::connection()->flushQueryLog();

            $csv->setOffset($offset)->setLimit($batchAantal);

            if ($command->getOutput()->isVerbose()) {
                $command->info("Memory now at: " . memory_get_peak_usage());
            }

            $csvResults = $csv->fetchAll(function ($row) use ($feed, $header, &$creationCount) {

                $insert                   = array_combine($header, $row);
                $insert['productfeed_id'] = $feed->id;

                Product::create($insert);

                $creationCount++;
            });

            $aantalResultaten = count($csvResults);
            $command->info("Totaal verwerkt: " . $creationCount . ' / ' . $feed->products);
            $offset += $aantalResultaten;

            // force end
            if ($aantalResultaten != $batchAantal) {
                break;
            }

        }

        if (File::exists($fileLocation)) {
            $command->info(sprintf("Deleting file '%s'", $fileLocation));
            File::delete($fileLocation);
        }
    }

    /**
     * Download remote bestand en sla deze op als csv file
     *
     * @param $url
     * @param $fileLocation
     * @param $command
     *
     * @return mixed
     * @throws \Exception
     */
    function downloadAndSaveFeed($url, $fileLocation, $command)
    {
        $file = fopen($fileLocation, 'w+');
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE           => $file,
            CURLOPT_TIMEOUT        => config('daisycon.timeout'),
            CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
        ));

        $command->info(sprintf("Saving '%s' to '%s'", $url, $fileLocation));
        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        return $response;
    }
}