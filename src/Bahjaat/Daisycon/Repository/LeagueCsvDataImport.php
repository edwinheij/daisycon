<?php
/**
 * User: Edwin Heij
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

use Bahjaat\Daisycon\Repository\DataImportInterface;
use League\Csv\Reader;
use Bahjaat\Daisycon\Models\Data;
use Config;
use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Symfony\Component\Console\Output\ConsoleOutput;

//use League\Csv\Reader;

class LeagueCsvDataImport implements DataImportInterface
{

    /**
     * @var ConsoleOutputInterface
     */
    private $console;

    public function __construct(ConsoleOutput $console)
    {

        $this->console = $console;
    }

    /**
     * @param $url
     * @param $program_id
     * @param $feed_id
     * @param $custom_categorie
     *
     * @throws \Exception
     */
    public function importData($url, $program_id, $feed_id, $custom_categorie)
    {
        $fileLocation = storage_path() . '/' . $program_id . '.' . $feed_id . '.csv';

        $this->downloadAndSaveFeed($url, $fileLocation);

        $this->filterBestand($fileLocation);

        $fields_wanted_from_config = DaisyconHelper::getDatabaseFieldsToImport();

        $offset = 1; // initieel op 1 om header te ontlopen
        $batchAantal = 1000;

        $csv = Reader::createFromPath($fileLocation);
        $csv->setDelimiter(';');
        $csv->setEnclosure('"');

        $creationCount = 0;

        while (true) {
            // Flushing the QueryLog anders kan de import te veel geheugen gaan gebruiken
            \DB::connection()->flushQueryLog();

            $csv->setOffset($offset)->setLimit($batchAantal);

            $this->console->writeln("Memory now at: " . memory_get_peak_usage());

            $csvResults = $csv->fetchAll(function ($row) use ($fields_wanted_from_config, $program_id, $feed_id, $custom_categorie, &$creationCount) {

                if (count($row) != count($fields_wanted_from_config)) return;

                try {
                    $inserted_array = array_merge(
                        array_combine(
                            $fields_wanted_from_config,
                            $row
                        ),
                        array(
                            'program_id' => $program_id,
                            'feed_id' => $feed_id,
                            'custom_categorie' => $custom_categorie
                        )
                    );
                    Data::create(
                        $inserted_array
                    );

                    $creationCount++;
                } catch (Exception $e) {
                    echo $e->getMessage() . PHP_EOL;
                } catch (\ErrorException $e) {
                    echo $e->getMessage() . PHP_EOL;
                }

            });

            $aantalResultaten = count($csvResults);
            $this->console->writeln("Totaal verwerkt: " . $creationCount);
            $offset += $aantalResultaten;


            if ($aantalResultaten != $batchAantal) break; // forceer einde

        }

        Data::where(function ($query) {
            $query->whereTitle('title')
                ->orWhere('title', 'like', '#%');
        })->delete();

        Data::whereTemp(null)->update(array('temp' => 1));

        \File::delete($fileLocation);
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
        fclose($reading);
        fclose($writing);
        rename($fileToWrite, $fileToRead);
        return;
    }

    /**
     * Download remote bestand en sla deze op als csv file
     *
     * @param $url
     * @param $fileLocation
     *
     * @return mixed
     * @throws \Exception
     */
    function downloadAndSaveFeed($url, $fileLocation)
    {
        $file = fopen($fileLocation, 'w+');
        $curl = curl_init($url);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_BINARYTRANSFER => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FILE => $file,
//            CURLOPT_TIMEOUT        => 120,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception('Curl error: ' . curl_error($curl));
        }

        return $response;
    }


}