<?php

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Bahjaat\Daisycon\Models\Productinfo;
use File;
use Illuminate\Database\QueryException;
use League\Csv\Reader;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Models\Product;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Models\Productfeed;

class LeagueCsvDataImport implements DataImportInterface
{

    /**
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
     * @param \Bahjaat\Daisycon\Models\Productfeed $feed
     * @param \Illuminate\Console\Command          $command
     */
    public function importfeed(Productfeed $feed, Command $command)
    {
        $fileLocation = storage_path() . '/temp.csv';

        $this->showTableDetails($feed, $command);

        try {
            $this->downloadAndSaveFeed($feed->url, $fileLocation, $command);
        } catch (\Exception $e) {
            $command->error($e->getMessage());
            return;
        }

        $offset      = 1; // to skip header
        $batchAantal = config('daisycon.chunksize', 500);

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

            $productinfoFields = DaisyconHelper::getProductinfoFields();

            $csvResults = $csv->fetchAll(function ($row) use (
                $feed,
                $header,
                $productinfoFields,
                $command,
                &$creationCount
            ) {
                $insert                   = array_combine($header, $row);
                $insert['productfeed_id'] = $feed->id;

                foreach ($productinfoFields as $k => $field) {
                    if (array_key_exists($field, $insert)) {
                        $productinfoFields[$field] = $insert[$field];
                        unset($insert[$field]);
                    }
                    unset($productinfoFields[$k]);
                }

                $insert['daisycon_unique_id'] = $productinfoFields['daisycon_unique_id'];

                $productinfoFields['program_id'] = $insert['id'];
                unset($insert['id']);

                try {
                    $insert = array_filter($insert);

                    foreach ($insert as $k => $v) {
                        if ($v == 'true') {
                            $insert[$k] = 1;
                        }
                        if ($v == 'false') {
                            $insert[$k] = 0;
                        }
                    }

                    if (isset($insert['longitude'])) {
                        $insert['destination_longitude'] = $insert['longitude'];
                        unset($insert['longitude']);
                    }
                    if (isset($insert['latitude'])) {
                        $insert['destination_latitude'] = $insert['latitude'];
                        unset($insert['latitude']);
                    }

                    $product = Product::updateOrCreate([
                        'daisycon_unique_id' => $insert['daisycon_unique_id']
                    ], $insert);

                    $productinfoFields['program_id'] = $feed->program->id;
                    $productinfoFields = array_filter($productinfoFields);

                    Productinfo::updateOrCreate([
                        'daisycon_unique_id' => $product->daisycon_unique_id
                    ], $productinfoFields);

                    $creationCount++;
                } catch (QueryException $e) {
                    $command->error($e->getMessage());
                    \Log::error($e->getMessage());
                    ksort($insert);
                    \Log::error($insert);
                    return;
                }
            });

            $resultCount = count($csvResults);
            $command->info("Products processed: " . $creationCount . ' / ' . $feed->products);
            $offset += $resultCount;

            // force end
            if ($resultCount != $batchAantal) {
                break;
            }

        }

        if (File::exists($fileLocation)) {
            if ($command->getOutput()->isVerbose()) {
                $command->info(sprintf("Deleting file '%s'", $fileLocation));
            } else {
                $command->info('Deleting temporary file');
            }
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

        if ($command->getOutput()->isVerbose()) {
            $command->info(sprintf("Saving '%s' to '%s'", $url, $fileLocation));
        } else {
            $command->info('Saving temporary file');
        }
        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        return $response;
    }

    /**
     * @param \Bahjaat\Daisycon\Models\Productfeed $feed
     * @param \Illuminate\Console\Command          $command
     */
    protected function showTableDetails(Productfeed $feed, Command $command)
    {
        $command->table([
            'Feed id',
            'Program id',
            'Program name'
        ], [
            [
                $feed->id,
                $feed->program->id,
                $feed->program->name,
            ]
        ]);
    }
}