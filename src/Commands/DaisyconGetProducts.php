<?php namespace Bahjaat\Daisycon\Commands;

use Bahjaat\Daisycon\Repository\DaisyconFeed;
use Config;
use Illuminate\Console\Command;

use Illuminate\Console\OutputStyle;
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
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var \Bahjaat\Daisycon\Repository\DataImportInterface
     */
    private $data;

    /**
     * Create a new command instance.
     *
     * @param \Bahjaat\Daisycon\Repository\DataImportInterface $data
     */
    public function __construct(DataImportInterface $data)
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $program_id = $this->argument('program_id');

        if (!empty($program_id)) {
            $this->info('Importing products for program ' . $program_id);

            $programs = Program::whereId($program_id)->whereHas('subscription', function($query) {
                return $query->approved();
            });
        } else {
            $this->info('Importing products for all programs');

            $programs = Program::whereHas('subscription', function($query) {
                return $query->approved();
            });
        }


        $programs->get()->each(function($program) {
//        $this->info('Importing products for program ' . $program->id);

//            $this->feed->import()
            if ( ! $program->productfeeds()->count()) {
                $this->info('No productfeeds for ' . $program->name);
                return;
            }

            $this->data->import($program, $this);











            /*$program->productfeeds->map(function ($productfeed) use ($program) {
                $tableData = [
                    'Programma ID' => $program->id,
                    'Programmas' => $program->name,
                    'Product count' => $productfeed->products,
                ];

                $this->table(array_keys($tableData), [array_values($tableData)]);

                $this->feed->import($productfeed);

                $this->info('Products imported into the database: ' . $productfeed->products()->count());
                $this->info('// --');
            });*/
        });

    }

}
