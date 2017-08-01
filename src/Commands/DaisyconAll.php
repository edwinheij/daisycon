<?php

namespace Bahjaat\Daisycon\Commands;

use CountrycodesTableSeeder;
use Illuminate\Console\Command;

class DaisyconAll extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do it all together with only one command';

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
    public function handle()
    {
        $this->info('Starting to get it all together! This may take a while...');

        (new CountrycodesTableSeeder)->run();

        $this->call('daisycon:get-programs');
        $this->call('daisycon:get-subscriptions');
        $this->call('daisycon:get-feeds');
        $this->call('daisycon:get-products', [
//            'program_id' => ..
        ]);

        $this->info('Finished gettings it all. Enjoy!');
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
