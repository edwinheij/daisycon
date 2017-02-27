<?php namespace Bahjaat\Daisycon\Helper;

use App;
use Config;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\ConsoleOutput;


class DaisyconHelper
{

    /*static function getApiOptions()
    {
        $options = array(
            'login' => Config::get("daisycon.username"),
            'password' => md5(Config::get("daisycon.password")),
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'encoding' => 'UTF-8',
            'trace' => 1,
            'cache_wsdl' => WSDL_CACHE_NONE // WSDL_CACHE_DISK / WSDL_CACHE_NONE
        );
        return $options;
    }*/

    static function getDatabaseFieldsToImport()
    {
        return Config::get("daisycon.db_fields_to_import");
    }

    static function getDatabaseFields()
    {
        return array_merge(
            self::getDatabaseFieldsToImport(),
            Config::get('daisycon.custom_db_fields_to_import')
        );
    }

    /**
     * Perform a request to the daisycon backend
     *
     * @param null $resourceUrl
     * @param array $options
     *
     * @return array
     */
    static function getRestAPI($resourceUrl = null, $options = [])
    {
        $options['per_page'] = $options['per_page'] > 50 ? 50 : $options['per_page'];

        $output = new ConsoleOutput;

        $publisher_id = Config::get("daisycon.publisher_id");

        $username = Config::get("daisycon.username");
        $password = Config::get("daisycon.password");
        $timeout = Config::get("daisycon.timeout");

        $uri = sprintf('https://services.daisycon.com/publishers/%s/', $publisher_id);

        $client = new Client([
            'base_uri' => $uri,
            'timeout' => $timeout,
            'auth' => [$username, $password, 'basic'],
            'query' => $options,
            'verify' => App::environment() == 'local' ? true : false,
            'debug' => App::environment() == 'local' ? true : false,
        ]);

        try {
            $response = $client->request('GET', $resourceUrl);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                return array(
                    'code' => $statusCode,
                    'response' => json_decode((string)$response->getBody())
                );
            }

        } catch (RequestException $e) {
            $output->writeln(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $output->writeln(Psr7\str($e->getResponse()));
            }
        }

    }

    public static function changeProgramURL($url)
    {
        $media_id = Config::get("daisycon.media_id");
        $sub_id = Config::get("daisycon.sub_id");

        $changeArray = array(
            '#MEDIA_ID#' => $media_id,
            '#SUB_ID#' => $sub_id
        );

        return str_replace(
            array_keys($changeArray),
            array_values($changeArray),
            $url
        );
    }
}
