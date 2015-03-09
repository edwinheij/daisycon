<?php
/**
 * User: Edwin Heij
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

use Maatwebsite\Excel\Facades\Excel;
use Bahjaat\Daisycon\Models\Data;
use Config;

class CsvDataImport implements DataImportInterface {

    /**
     *
     */
    public function importData($url, $program_id, $feed_id, $custom_categorie)
    {
        $fileLocation = storage_path() . '/'.$program_id.'.'.$feed_id.'.csv';

        $this->downloadAndSaveFeed($url, $fileLocation);
        $this->filterBestand($fileLocation);

        $chunkSize = Config::get('daisycon::config.chunksize', 500);
        Excel::filter('chunk')->load($fileLocation)->chunk($chunkSize, function($results) use ($program_id, $feed_id, $custom_categorie)
        {

            foreach($results as $row)
            {
                /**
                 * Lege values eruit filteren
                 */
                $arr = array_filter(
                    $row->toArray()
                );

                try {
                    /**
                     * Merge 'program_id' in gegevens uit XML
                     */
                    $inserted_array = array_merge($arr,
                        array(
                            'program_id' => $program_id,
                            'feed_id' => $feed_id,
                            'custom_categorie' => $custom_categorie
                        )
                    );
                    Data::create(
                        $inserted_array
                    );
                } catch (Exception $e) {
                    dd($e->getMessage());
                }

            }
        });

        Data::where(function ($query) {
            $query->whereTitle('title')
                ->orWhere('title', 'like', '#%');
        })->delete();

        Data::whereTemp(0)->update(array('temp' => 1));
        \File::delete($fileLocation);
    }

    /**
     * Download remote bestand en sla deze op als csv file
     *
     * @param $url
     * @param $fileLocation
     * @return mixed
     * @throws \Exception
     */
    function downloadAndSaveFeed($url, $fileLocation)
    {
        $file = fopen($fileLocation, 'w+');
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE           => $file,
//            CURLOPT_TIMEOUT        => 120,
            CURLOPT_USERAGENT      => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
        ));

        $response = curl_exec($curl);

        if($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        return $response;
    }

    /**
     * Haal regels weg die beginnen met een hash (#)
     *
     * @param null $file
     */
    function filterBestand($file = null)
    {
        if (is_null($file)) return;
        $fileToRead = $file;
        $fileToWrite = $file . '.tmp';

        $reading = fopen($fileToRead, 'r');
        $writing = fopen($fileToWrite, 'w');
        while (!feof($reading)) {
            $line = fgets($reading);
            if (substr($line, 0, 1) == "\"") {
                fputs($writing, $line);
            }
        }
        fclose($reading); fclose($writing);
        rename($fileToWrite, $fileToRead);
        return;
    }


}