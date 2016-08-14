<?php

namespace Bahjaat\Daisycon\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

use Bahjaat\Daisycon\Models\Feed as Feed;
use Bahjaat\Daisycon\Models\Subscription as Subscription;
use Bahjaat\Daisycon\Helper\DaisyconHelper;

class DaisyconGetFeeds extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'daisycon:get-feeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all feeds into the database.';

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
        $media_id = Config::get("daisycon.media_id");
        $sub_id = Config::get("daisycon.sub_id");

        $page = 1;
        $per_page = 50;
        $notLastPage = true;

        $options = array(
            'page' => $page,
            'per_page' => $per_page,
            'media_id' => $media_id,
            'placeholder_media_id' => $media_id,
            'placeholder_subid' => $sub_id
        );

        $this->info('Start importing feeds into the database');

        while ($notLastPage) {

            $APIdata = DaisyconHelper::getRestAPI("productfeeds", $options);

            if (is_array($APIdata)) {

                $resultCount = count($APIdata['response']);

                if ($resultCount > 0) {

                    if ($page == 1) {
                        $this->info('Truncate database table');
                        Feed::truncate();
                    }

                    $this->comment($resultCount . ' programs loaded');

                    foreach ($APIdata['response'] as $feedinfo) {
                        $feedinfo = (array)$feedinfo;
                        $feedinfo['feed_id'] = $feedinfo['id'];
                        Feed::create($feedinfo);
                    }
                } else {
                    return $this->comment('Geen feeds gevonden');
                }
            }
            if (isset($resultCount) && $resultCount < $per_page) $notLastPage = false;
            $options['page'] = $page++;
        }
        $count = Feed::all()->count();
        return $this->info($count . ' feeds imported');
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
