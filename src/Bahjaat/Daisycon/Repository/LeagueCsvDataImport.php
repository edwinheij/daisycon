<?php
/**
 * User: Edwin Heij
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Repository\DataImportInterface;
//use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;
use Bahjaat\Daisycon\Models\Data;
use Config;
use PDO;

class LeagueCsvDataImport implements DataImportInterface {

    /**
     *
     */
    public function importData($url, $program_id, $feed_id, $custom_categorie)
    {
        //LOAD DATA LOCAL INFILE 'C:\\Code\\superdeluxvakanties.dev\\app\\storage\\sunweb.csv' REPLACE INTO TABLE `daisycon.live.dev`.`data` CHARACTER SET utf8 FIELDS TERMINATED BY ';' ENCLOSED BY '"' ESCAPED BY '"' LINES TERMINATED BY '\n' (`title`, `link`, `description`, `accommodation_name`, `slug_accommodation_name`, `accommodation_type`, `min_nr_people`, `location_description`, `stars`, `minimum_price`, `maximum_price`, `lowest_price`, `continent_of_destination`, `slug_continent_of_destination`, `country_of_destination`, `slug_country_of_destination`, `country_link`, `region_of_destination`, `slug_region_of_destination`, `region_link`, `city_of_destination`, `slug_city_of_destination`, `city_link`, `longitude`, `latitude`, `continent_of_origin`, `slug_continent_of_origin`, `country_of_origin`, `slug_country_of_origin`, `city_of_origin`, `slug_city_of_origin`, `port_of_departure`, `img_small`, `img_medium`, `img_large`, `board_type`, `tour_operator`, `transportation_type`, `departure-date`, `departure_date`, `end_date`, `duration`, `daisycon_unique_id`, `internal_id`, `unique_integer`, `update_hash`);

        $fileLocation = storage_path() . '/'.$program_id.'.'.$feed_id.'.csv';
        $contents = file_get_contents($url);
        $contents = $this->verwijderBovensteRegels(2, $contents);
        \File::put($fileLocation, $contents);

        $user = 'root';
        $pass = '';
        $dbh = new \PDO('mysql:host=localhost;dbname=daisycon.dev', $user, $pass);

        $sth = $dbh->prepare(
            "INSERT INTO `daisycon.dev`.`data` (" .
              "`title`, `link`, `description`" . // `accommodation_name`, `slug_accommodation_name`, `accommodation_type`, `min_nr_people`, `location_description`, `stars`, `minimum_price`, `maximum_price`, `lowest_price`, `continent_of_destination`, `slug_continent_of_destination`, `country_of_destination`, `slug_country_of_destination`, `country_link`, `region_of_destination`, `slug_region_of_destination`, `region_link`, `city_of_destination`, `slug_city_of_destination`, `city_link`, `longitude`, `latitude`, `continent_of_origin`, `slug_continent_of_origin`, `country_of_origin`, `slug_country_of_origin`, `city_of_origin`, `slug_city_of_origin`, `port_of_departure`, `img_small`, `img_medium`, `img_large`, `board_type`, `tour_operator`, `transportation_type`, `departure-date`, `departure_date`, `end_date`, `duration`, `daisycon_unique_id`, `internal_id`, `unique_integer`, `update_hash`
            ") VALUES (:title, :link, :description)"
        );

        $csv = Reader::createFromPath($fileLocation);
        $csv->setDelimiter(';');
        $csv->setEnclosure('"');
        $csv->setOffset(1); //because we don't want to insert the header
        $c = 0;
        $nbInsert = $csv->each(function ($row) use (&$sth) {
            if (!isset($row[0])) return;
            global $c;
            //Do not forget to validate your data before inserting it in your database
            $sth->bindValue(':title', $row[0], \PDO::PARAM_STR);
            $sth->bindValue(':link', $row[1], \PDO::PARAM_STR);
            $sth->bindValue(':description', $row[2], \PDO::PARAM_STR);

            return $sth->execute(); //if the function return false then the iteration will stop
        });

        /*$csv = Reader::createFromPath($fileLocation);
        $csv->setDelimiter(';');
        $csv->setEnclosure('"');
        $csv->setOffset(2); //because we don't want to insert the header

        $headers = $csv->fetchOne();
        $errorCount = 0;
        $all = $csv->fetchAll();
        foreach ($all as $row)
        {
            try {
                print_r(array_combine( $headers, $row ));
                Data::create(
                    array_combine( $headers, $row )
                );
            } catch (\Exception $e) {
                continue;
            }
        }*/

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