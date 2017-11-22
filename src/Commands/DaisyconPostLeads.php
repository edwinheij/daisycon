<?php

namespace Bahjaat\Daisycon\Commands;

use Config;
use Illuminate\Console\Command;
use Bahjaat\Daisycon\Repository\Daisycon;

class DaisyconPostLeads extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daisycon:post-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leads exporteren naar Daisycon.';

    protected $daisycon;

    /**
     * Create a new command instance.
     *
     * @param \Bahjaat\Daisycon\Repository\Daisycon $daisycon
     */
    public function __construct(Daisycon $daisycon)
    {
        parent::__construct();
        $this->daisycon = $daisycon;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Exporting leads.');

        $data = [
            'transaction_data' => [
                'program_id' => 10744,
                'media_id' => Config::get('daisycon.media_id'),
                'published_tag' => 'tag',
                'published_tag_extra_1' => 'tag extra 1',
                'published_tag_extra_2' => 'tag extra 2',
                'action_id' => rand(1, 99),
                'ip' => '10.0.0.1',
                'zipcode' => '1234AB',
                'country' => 'NL',
                'gender' => 'M',
                'birthdate' => '1980-10-10',
            ],
            'questions' => [
                [
                    'question' => 'addition',
                    'answer' => 'a'
                ],[
                    'question' => 'email',
                    'answer' => 'email@domain.com'
                ],[
                    'question' => 'gender',
                    'answer' => 'M'
                ],[
                    'question' => 'house_number',
                    'answer' => rand(1, 200)
                ],[
                    'question' => 'initials',
                    'answer' => 'ET'
                ],[
                    'question' => 'insertion',
                    'answer' => 'insertion'
                ],[
                    'question' => 'phone',
                    'answer' => '0123456789'
                ],[
                    'question' => 'surname',
                    'answer' => 'achternaam'
                ],[
                    'question' => 'zipcode',
                    'answer' => '5678AZ'
                ]
            ]
        ];

        $this->daisycon
            ->postLeads(json_encode($data));

        $this->info('Ready');
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
