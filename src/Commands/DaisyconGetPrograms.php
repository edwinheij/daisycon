<?php

namespace Bahjaat\Daisycon\Commands;

use Config;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Bahjaat\Daisycon\Models\Program as Program;

class DaisyconGetPrograms extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'daisycon:get-programs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import programs into the database.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $page = 1;
        $per_page = 50;
        $notLastPage = true;

        while ($notLastPage) {

            $options = array(
                'productfeed' => 'true',
                'page' => $page,
                'per_page' => $per_page,
            );

            $APIdata = DaisyconHelper::getRestAPI("programs", $options);

            if (is_array($APIdata)) {
                $resultCount = count($APIdata['response']);
                if ($resultCount > 0) {
                    if ($page == 1) {
                        $this->info('Clear database table');
                        Program::truncate();
                    }
                    $this->comment($resultCount . ' programs loaded');

                    foreach ($APIdata['response'] as $program) {
                        $program = (array)$program;

                        $program['program_id'] = $program['id'];
                        unset($program['id']);

                        $program['description'] = $program['descriptions'][0]->description;
                        $program['url'] = DaisyconHelper::changeProgramURL($program['url']);

                        Program::create((array)$program);
                    }
                } else {
                    $this->comment('No programs found');
                }
            }
            if ($resultCount < $per_page) $notLastPage = false;
            $page++;
        }
        $count = Program::all()->count();
        return $this->info($count . ' programs imported');
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
