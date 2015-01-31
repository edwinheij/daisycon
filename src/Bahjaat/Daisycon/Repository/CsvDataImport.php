<?php
/**
 * User: Edwin Heij
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Repository\DataImportInterface;
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
        $contents = file_get_contents($url);
        $contents = $this->verwijderBovensteRegels(2, $contents);
        \File::put($fileLocation, $contents);

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