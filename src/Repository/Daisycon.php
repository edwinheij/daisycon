<?php

namespace Bahjaat\Daisycon\Repository;

use App;
use Bahjaat\Daisycon\Models\Lead;
use Bahjaat\Daisycon\Models\Leadrequirement;
use Bahjaat\Daisycon\Models\Productfeed;
use Config;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Models\Subscription;
use GuzzleHttp\Client as GuzzleClient;
use Bahjaat\Daisycon\Repository\Exceptions\NoContentException;
use Illuminate\Database\QueryException;

class Daisycon
{
    protected $guzzleClient;

    protected $parameters;

    protected $allPages = true;

    protected $requestMethod = 'GET';

    /**
     * Daisycon constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $publisher_id = Config::get("daisycon.publisher_id");
        $sandbox      = Config::get("daisycon.sandbox") ? '-sandbox' : '';

        $uri = "https://services{$sandbox}.daisycon.com/publishers/{$publisher_id}/";

        $settings = [
            'base_uri' => $uri,
            'timeout'  => Config::get("daisycon.timeout"),
            'auth'     => $this->authentication(),
            'query'    => $options,
            'verify'   => App::environment() == 'local' ? true : false,
            'debug'    => Config::get('app.debug'),
        ];

        $this->parameters = [
            'page'     => 1,
            'per_page' => 100
        ];

        $this->guzzleClient = new GuzzleClient($settings);
    }

    /**
     * Post leads
     *
     * @param $data
     */
    public function postLeads($data)
    {
        $uri = "leads";

        $class = Lead::class;

        $this->requestMethod = 'POST';

        $this->doRequest($uri, $class, $data);
    }

    /**
     * Subscriptions ophalen
     *
     * @return void
     */
    public function getSubscriptions()
    {
        $media_id = Config::get('daisycon.media_id');
        $uri      = "media/{$media_id}/subscriptions";
        $class    = Subscription::class;

        $this->setParameter(['status' => 'approved']);

        $this->doRequest($uri, $class);
    }

    /**
     * Programma's ophalen
     *
     * @return void
     */
    public function getPrograms()
    {
        $uri   = 'programs';
        $class = Program::class;

        $this->setParameter([
            'media_id'    => Config::get('daisycon.media_id')
        ]);

        $this->doRequest($uri, $class);
    }

    /**
     * Ophalen van 1 enkel programma (nog niet in gebruik)
     *
     * @param $id
     *
     * @return void
     */
    public function getProgram($id)
    {
        $uri   = 'programs/' . $id;
        $class = Program::class;

        $this->setParameter([
            'media_id'    => Config::get('daisycon.media_id'),
            'productfeed' => 'true'
        ]);

        $this->doRequest($uri, $class);
    }

    /**
     * Programma's ophalen
     *
     * @return void
     */
    public function getProductfeeds()
    {
        $uri   = 'productfeeds.v2/program';
        $class = Productfeed::class;

        $this->setParameter(['media_id' => Config::get('daisycon.media_id')]);

        $this->doRequest($uri, $class);
    }

    /**
     * Programma's ophalen
     *
     * @return void
     */
    public function getLeadrequirements()
    {
        $uri   = 'leadrequirements';
        $class = Leadrequirement::class;

        $response = $this->doRequest($uri, $class);
    }

    /**
     * Request uitvoeren naar Daisycon via APi
     *
     * @param string $uri
     * @param string $class
     * @param        $data
     *
     * @return mixed
     */
    protected function doRequest($uri, $class, $data = null)
    {
        try {
            if (is_null($data)) {
                $options = [
                    'form_params' => $this->parameters
                ];
            } else {
                $options = [
                    'body' => $data
                ];
            }

            $response = $this->guzzleClient->request($this->requestMethod, $uri, $options);
            $this->handleResponse($response, $class);

            if ( ! $this->allPages) {
                return true;
            }

            // volgende pagina
            $this->parameters['page']++;

            return $this->doRequest($uri, $class);

        } catch (NoContentException $e) {
            return true;
        }
    }

    /**
     * Response converteren
     *
     * @param object|array $response
     * @param string       $class
     *
     * @throws \Bahjaat\Daisycon\Repository\Exceptions\NoContentException
     */
    protected function handleResponse($response, $class)
    {
        if ($response->getStatusCode() == 204) {
            throw new NoContentException();
        }

        $results = json_decode((string)$response->getBody());

        $classShortname = (new \ReflectionClass($class))->getShortName();

        if (method_exists($this, "store{$classShortname}")) {
            call_user_func([$this, "store{$classShortname}"], $results, $class);
        } else  {
            $this->storeResults($results, $class);
        }

    }

    protected function storeLeadrequirement($results, $class) {
        collect($results)->map(function($result) {
            Leadrequirement::where('program_id', $result->program_id)->delete();

            foreach($result->questions as $question) {
                Leadrequirement::firstOrCreate([
                    'program_id' => $result->program_id,
                    'question' => $question->question,
                ],[
                    'required' => $question->required
                ]);
            }

        });

        throw new NoContentException();
    }

    protected function storeResults($results, $class)
    {
        try {
            foreach ($results as $result) {
                $result = (array)$result;

                //            var_dump($result);
                /*
                            if (array_key_exists('id', $result)) {
                                $classShortname = strtolower((new \ReflectionClass($class))->getShortName());
                                $result[$classShortname . '_id'] = $result['id'];
                                unset($result['id']);
                            }
                */

                //            print_r($result);
                //            die();

                $class::create($result);
            }
        } catch (QueryException $e) {
            //
        }
    }

    /**
     * Get authentication from config
     *
     * @return array
     */
    protected function authentication(): array
    {
        return [
            Config::get("daisycon.username"),
            Config::get("daisycon.password"),
            'basic'
        ];
    }

    /**
     * Setter for allPages
     *
     * @param bool $allPages
     *
     * @return $this
     */
    public function allPages(bool $allPages)
    {
        $this->allPages = $allPages;

        return $this;
    }

    /**
     * Parameters zetten voor request
     *
     * @param array $parameter
     *
     * @return $this
     */
    public function setParameter(array $parameter)
    {
        foreach ($parameter as $param => $value) {
            $this->parameters[$param] = $value;
        }

        return $this;
    }
}