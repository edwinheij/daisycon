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
        $contents = file_get_contents($url);
        $contents = $this->verwijderBovensteRegels(2, $contents);
        \File::put($fileLocation, $contents);
        $sql = "LOAD DATA INFILE '" . addslashes($fileLocation) . "' INTO TABLE `daisycon.dev`.`data` CHARACTER SET utf8 FIELDS TERMINATED BY ';' ENCLOSED BY '\"' ESCAPED BY '\"' LINES TERMINATED BY '\\n' (`title`, `link`, `description`, `accommodation_name`, `accommodation_type`, `min_nr_people`, `location_description`, `stars`, `minimum_price`, `maximum_price`, `lowest_price`, `continent_of_destination`, `country_of_destination`, `country_link`, `region_of_destination`, `region_link`, `city_of_destination`, `city_link`, `longitude`, `latitude`, `continent_of_origin`, `country_of_origin`, `city_of_origin`, `port_of_departure`, `img_small`, `img_medium`, `img_large`, `board_type`, `tour_operator`, `transportation_type`, `departure-date`, `departure_date`, `end_date`, `duration`, `daisycon_unique_id`, `internal_id`, `unique_integer`, `update_hash`)
        SET `created_at` = NOW(), `updated_at` = NOW(), `program_id` = ".$program_id.", `feed_id` = ".$feed_id.", `custom_categorie` = '" . $custom_categorie . "'
        ";
        DB::connection()->getPdo()->exec($sql);
        Data::where('title', 'title')->delete();
        \File::delete($fileLocation);
    }

    function verwijderBovensteRegels($hoeveelRegels = 2, $contents)
    {
        $lineEnding = "\n";
        $explode = explode($lineEnding, $contents);
        for ($x = 0; $x < $hoeveelRegels; $x++)
            array_shift($explode);
        return implode($lineEnding, $explode);
    }

}