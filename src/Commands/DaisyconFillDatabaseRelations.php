<?php

namespace Bahjaat\Daisycon\Commands;

use Bahjaat\Daisycon\Models\Subscription;
use Illuminate\Console\Command;

class DaisyconFillDatabaseRelations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:relations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Connect tables (fill pivot tables)';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $this->info('Truncate pivot table');
        \DB::table('program_subscription')->truncate();

        $this->info('Connecting subscriptions to programs');
        Subscription::chunk(10, function($chunk) {
            foreach ($chunk as $subscription) {
                $subscription->programs()->sync($subscription->program_ids);
            }
        });

        $this->info('Ready');
    }
}
