<?php namespace Bahjaat\Daisycon\Commands;

use Bahjaat\Daisycon\Models\Productinfo;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Models\Program;
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

            if ( ! $program->productfeeds()->count()) {
                $this->info('No productfeeds for ' . $program->name);
                return;
            }

            $this->data->import($program, $this);
        });
    }

}
