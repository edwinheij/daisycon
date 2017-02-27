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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:import-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import URI\'s (XML, CSV ...) from feeds into data table';

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
     * @param DataImportInterface $data
     */
    public function __construct(DataImportInterface $data)
    {
        parent::__construct();
        $this->data = $data;

//        \DB::listen(function($query) {
//            print_r($query->sql);
//            print_r($query->bindings);
//        });
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
    public function handle()
    {
        $this->info('Truncate data table');
        Data::truncate();

        $this->info('Searching for feeds');

        $activeProgramsFromDB = ActiveProgram::with('program.feeds')->where('status', 1)->get();

        $fields_wanted_from_config = DaisyconHelper::getDatabaseFieldsToImport();

        if (count($activeProgramsFromDB) > 0) {
            foreach ($activeProgramsFromDB as $activeProgram) {
                if (!empty($activeProgram->program->feeds) || !empty($activeProgram->program->name)) {
                    foreach ($activeProgram->program->feeds as $feed) {
                        $this->info($activeProgram->program->name . ' - ' . $feed->name);
//                        $url = $feed->{"feed_link_" . strtolower(Config::get('daisycon.feed_type', 'csv'))} .
                        $url = $feed->url .
                            '&f=' . implode(';', $fields_wanted_from_config) .
                            '&encoding=' . Config::get("daisycon.encoding") .
                            '&general=true' .
                            '&nohtml=' . (Config::get("daisycon.accept_html", false) ? 'false' : 'true');
                        $url = str_replace('#LOCALE_ID#', 1, $url);
                        $this->info($url);

                        $program_id = $activeProgram->program->program_id;
                        $feed_id = $feed->feed_id;
                        $custom_categorie = $activeProgram->custom_categorie;
                        $this->data->importData($url, $program_id, $feed_id, $custom_categorie);
                    }
                } else {
                    $this->info('Geen feeds en/of programma\'s in de database gevonden...');
                    continue;
                }
            }
        } else {
            return $this->info('Geen active programma\'s in de database gevonden...');
        }
//        $this->call('daisycon:fix-data');
        return $this->info('Verwerkt in ' . round(microtime(true) - LARAVEL_START, 2) . ' seconden');
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
