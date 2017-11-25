<?php

namespace Bahjaat\Daisycon\Repository;

use App;
use Bahjaat\Daisycon\Models\Productfeed;
use Config;
use Bahjaat\Daisycon\Models\Program;
use Bahjaat\Daisycon\Models\Subscription;
use GuzzleHttp\Client as GuzzleClient;
use Bahjaat\Daisycon\Repository\Exceptions\NoContentException;

class Daisycon
{
    protected $guzzleClient;

    protected $parameters;

    protected $allPages = true;

    /**
     * Daisycon constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $publisher_id = Config::get("daisycon.publisher_id");
        $uri          = "https://services.daisycon.com/publishers/{$publisher_id}/";

        $settings = [
            'base_uri' => $uri,
            'timeout'  => Config::get("daisycon.timeout"),
            'auth'     => $this->authentication(),
            'query'    => $options,
            'verify'   => App::environment() == 'local' ? true : false,
            'debug'    => false, //App::environment() == 'local' ? true : false,
        ];

        $this->parameters = [
            'page' => 1,
            'per_page' => 100
        ];

        $this->guzzleClient = new GuzzleClient($settings);
    }

    /**
     * Subscriptions ophalen
     *
     * @return void
     */
    public function getSubscriptions()
    {
        $media_id = Config::get('daisycon.media_id');
        $uri = "media/{$media_id}/subscriptions";
        $class = Subscription::class;

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
        $uri = 'programs';
        $class = Program::class;

        $this->setParameter([
            'media_id' => Config::get('daisycon.media_id'),
//            'productfeed' => 'true'
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
    public function getProgram($id) {
        $uri = 'programs/' . $id;
        $class = Program::class;

        $this->setParameter([
            'media_id' => Config::get('daisycon.media_id'),
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
        $uri = 'productfeeds.v2/program';
        $class = Productfeed::class;

        $this->setParameter(['media_id' => Config::get('daisycon.media_id')]);

        $this->doRequest($uri, $class);
    }

    /**
     * Request uitvoeren naar Daisycon via APi
     *
     * @param string $uri
     * @param string $class
     *
     * @return mixed
     */
    protected function doRequest($uri, $class) {
        try {
            $response = $this->guzzleClient->request('GET', $uri, [
                'form_params' => $this->parameters
            ]);
            $this->handleResponse($response, $class);

            if (!$this->allPages) return true;

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
     * @param string $class
     *
     * @throws \App\Daisycon\Exceptions\NoContentException
     */
    protected function handleResponse($response, $class) {
        if ($response->getStatusCode() == 204) {
            throw new NoContentException();
        }

        $results = json_decode((string)$response->getBody());

        $this->storeResults($results, $class);
    }

    protected function storeResults($results, $class)
    {
        foreach ($results as $result) {
            $result = (array) $result;

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
    public function setParameter(array $parameter) {
        foreach ($parameter as $param => $value) {
            $this->parameters[$param] = $value;
        }

        return $this;
    }
}