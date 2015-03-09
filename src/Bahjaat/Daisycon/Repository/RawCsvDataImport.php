<?php
/**
 * User: Edwin Heij
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Repository\DataImportInterface;
//use Maatwebsite\Excel\Facades\Excel;
//use League\Csv\Reader;
use Bahjaat\Daisycon\Models\Data;
use Config;
use PDO;
use DB;

class RawCsvDataImport implements DataImportInterface {

    public function importData($url, $program_id, $feed_id, $custom_categorie)
    {
        $fileLocation = storage_path() . '/'.$program_id.'.'.$feed_id.'.csv';
        $response = $this->downloadAndSaveFeed($url, $fileLocation);

        if ($response) {
//            $this->filterBestand($fileLocation);

            $pdo = DB::connection()->getPdo();

            $sql = "LOAD DATA INFILE '" . addslashes($fileLocation) . "'
            INTO TABLE `data`
            CHARACTER SET utf8 FIELDS TERMINATED BY ';' ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n'
            (`title`, `link`, `description`, `accommodation_name`, `accommodation_type`, `min_nr_people`, `location_description`, `stars`, `minimum_price`, `maximum_price`, `lowest_price`, `continent_of_destination`, `country_of_destination`, `country_link`, `region_of_destination`, `region_link`, `city_of_destination`, `city_link`, `longitude`, `latitude`, `continent_of_origin`, `country_of_origin`, `city_of_origin`, `port_of_departure`, `img_small`, `img_medium`, `img_large`, `board_type`, `tour_operator`, `transportation_type`, `departure-date`, `departure_date`, `end_date`, `duration`, `daisycon_unique_id`, `internal_id`, `unique_integer`, `update_hash`)
            SET
                `created_at` = NOW(),
                `updated_at` = NOW(),
                `program_id` = " . $program_id . ",
                `feed_id` = " . $feed_id . ",
                `custom_categorie` = '" . $custom_categorie . "'
            ";
//            DB::connection()->getPdo()->exec($sql);
            $pdo->exec($sql);

            Data::where(function ($query) {
                $query->whereTitle('title')
                    ->orWhere('title', 'like', '#%');
            })->delete();

//            DB::table('data')->update(array('temp' => 1));
            Data::whereTemp(0)->update(array('temp' => 1));
            \File::delete($fileLocation);
        }
    }

    function verwijderBovensteRegels_($hoeveelRegels = 2, $contents)
    {
        $lineEnding = "\n";
        $explode = explode($lineEnding, $contents);
        for ($x = 0; $x < $hoeveelRegels; $x++)
            array_shift($explode);
        return implode($lineEnding, $explode);
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
            if (substr($line, 0, 1) != "#") {
                fputs($writing, $line);
            }
        }
        fclose($reading); fclose($writing);
        rename($fileToWrite, $fileToRead);
        return;
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

}