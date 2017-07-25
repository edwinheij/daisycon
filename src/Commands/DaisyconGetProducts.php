<?php namespace Bahjaat\Daisycon\Commands;

use Bahjaat\Daisycon\Repository\DaisyconFeed;
use Config;
use Illuminate\Console\Command;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

use Bahjaat\Daisycon\Helper\DaisyconHelper;

use Bahjaat\Daisycon\Models\ActiveProgram;
use Bahjaat\Daisycon\Models\Countrycode;
use Bahjaat\Daisycon\Models\Data;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Models\Subscription;

use Bahjaat\Daisycon\Repository\DataImportInterface;

class DaisyconGetProducts extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:get-products {program_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from feeds into data table';

    /**
     * The program ID
     *
     * @var integer
     */
    protected $program_id;

    /**
     * @var mixed
     */
    protected $feed;

    /**
     * Create a new command instance.
     *
     * @param \Bahjaat\Daisycon\Repository\DaisyconFeed $feed
     */
    public function __construct(DaisyconFeed $feed)
    {
        parent::__construct();
        $this->feed = $feed;
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
        $this->info('Importing products');

        $program_id = $this->argument('program_id');

        if (!empty($program_id)) {
            $programs = Program::whereId($program_id)->whereHas('subscription', function($query) {
                return $query->approved();
            });
        } else {
            $programs = Program::whereHas('subscription', function($query) {
                return $query->approved();
            });
        }

        $programs->get()->each(function($program) {
            $program->productfeeds->map(function ($productfeed) use ($program) {
                $tableData = [
                    'Programma ID' => $program->id,
                    'Programma' => $program->name,
                    'Product count' => $productfeed->products,
                ];
                $this->table(array_keys($tableData), [array_values($tableData)]);

                $this->feed->import($productfeed);

                $this->info('Producten geïmporteerd in database: ' . $productfeed->products()->count());
                $this->info('// --');
            });
        });

    }

}
