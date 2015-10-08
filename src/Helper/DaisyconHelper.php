<?php namespace Bahjaat\Daisycon\Helper;

use Symfony\Component\Console\Output\ConsoleOutput;
use Config;
use App;


class DaisyconHelper
{

    static function getApiOptions()
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
    }

    static function getDatabaseFieldsToImport()
    {
        return Config::get("daisycon.db_fields_to_import");
    }

    static function getDatabaseFields()
    {
        return array_merge(
            Config::get('daisycon.db_fields_to_import'),
            Config::get('daisycon.custom_db_fields_to_import')
        );
    }

    static function getRestAPI($resourceUrl = '', Array $options = array())
    {
        $output = new ConsoleOutput;

        $publisher_id = Config::get("daisycon.publisher_id");
        $username = Config::get("daisycon.username");
        $password = Config::get("daisycon.password");

        $url = 'https://services.daisycon.com/publishers/' . $publisher_id . '/' . $resourceUrl;
        if (!empty($options)) {
            $query_string = http_build_query($options);
//			$output->writeln('Filter actief: ' . $query_string);
            $url .= '?' . $query_string;
            $output->writeln('Filter actief: ' . $url);
        }

        $ch = curl_init();
        $headers = array('Authorization: Basic ' . base64_encode($username . ':' . $password));

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (App::environment() == "local") {
            $output->writeln('CURLOPT_SSL_VERIFYPEER op false gezet ivm ophalen via local environment');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

        /**
         * Voor debugging kan onderstaande regel gebruikt worden
         * */
//		curl_setopt($ch, CURLOPT_VERBOSE, true); // gebruik dit voor test als er geen of onjuist resultaat terug komt

        try {
            $response = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($code == 200) {
                return array(
                    'code' => $code,
                    'response' => json_decode($response)
                );
            }
            curl_close($ch);
            throw new \Exception(
                '1. Waarschijnlijk niet geautoriseerd voor de API.' . PHP_EOL
                . '   Ga bij Daisycon naar de published omgeving en kies in het menu voor \'Account privileges\'' . PHP_EOL
                . '2. Certificaat probleem. CURL-optie \'CURLOPT_SSL_VERIFYPEER\' op false zetten (bijv. voor localhost development).'
            );
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
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

        return str_replace(array_keys($changeArray), array_values($changeArray), $url);
    }
}
