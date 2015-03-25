<?php namespace Bahjaat\Daisycon\Commands;

use Config;
use Illuminate\Console\Command;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

use Bahjaat\Daisycon\Helper\DaisyconHelper;

use Bahjaat\Daisycon\Models\ActiveProgram;
use Bahjaat\Daisycon\Models\Countrycode;
use Bahjaat\Daisycon\Models\Data;
use Bahjaat\Daisycon\Models\Feed;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Models\Subscription;

use Bahjaat\Daisycon\Repository\DataImportInterface;

class DaisyconImportData extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'daisycon:import-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data URL\'s (XML, CSV ...) uitlezen vanuit database en deze verwerken in de data tabel';

    /**
     * The program ID
     *
     * @var integer
     */
    protected $program_id;

    /**
     * @var mixed
     */
    public $data;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DataImportInterface $data)
    {
        parent::__construct();
        $this->data = $data;
    }

    protected function getProgramID()
    {
        return $this->program_id;
    }

    protected function setProgramID($id)
    {
        $this->program_id = $id;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Data tabel leeg maken');
        Data::truncate();

        $this->info('Opzoeken feeds');

        $activeProgramsFromDB = ActiveProgram::with('program.feeds')->where('status', 1)->get();

        $fields_wanted_from_config = DaisyconHelper::getDatabaseFieldsToImport();

        if (count($activeProgramsFromDB) > 0) {
            foreach ($activeProgramsFromDB as $activeProgram) {
                if (!empty($activeProgram->program->feeds) || !empty($activeProgram->program->name)) {
                    foreach ($activeProgram->program->feeds as $feed) {
                        $this->info($activeProgram->program->name . ' - ' . $feed->name);
                        $url = $feed->{"feed_link_" . strtolower(Config::get('daisycon.feed_type', 'csv'))} .
                            '&f=' . implode(';', $fields_wanted_from_config) .
                            '&encoding=' . Config::get("daisycon.encoding") .
                            '&general=true' .
                            '&nohtml=' . (Config::get("daisycon.html_toestaan", false) ? 'false' : 'true');
                        $program_id = $activeProgram->program->program_id;
                        $feed_id = $feed->feed_id;
                        $custom_categorie = $activeProgram->custom_categorie;
                        $this->data->importData($url, $program_id, $feed_id, $custom_categorie);
                    }
                } // if !empty $activeProgram->program->feeds
                else {
                    $this->info('Geen feeds en/of programma\'s in de database gevonden...');
                    continue;
                }
            }
        } else {
            return $this->info('Geen active programma\'s in de database gevonden...');
        }
        $this->call('daisycon:fix-data');
        return $this->info('Did IT in: ' . round(microtime(true) - LARAVEL_START, 2));
    }

    public function importData_oud($url, $program_id, $feed_id, $custom_categorie)
    {

        $CHUNK_SIZE = 1024;
        $stream = new Stream\Guzzle($url, $CHUNK_SIZE);
        $config = array(
            'uniqueNode' => 'item',
        );
        $parser = new Parser\UniqueNode($config);
        $streamer = new XmlStringStreamer($parser, $stream);

        while ($node = $streamer->getNode()) {

            $simpleXmlNode = simplexml_load_string($node, null, LIBXML_NOCDATA); // LIBXML_NOCDATA-trick from: http://dissectionbydavid.wordpress.com/2013/01/25/simple-simplexml-to-array-in-php/

            /**
             * Lege values eruit filteren
             */
            $arr = array_filter(
                (array)$simpleXmlNode
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

        } // while

        return;
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
