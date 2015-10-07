<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

//use Prewk\XmlStringStreamer;
//use Prewk\XmlStringStreamer\Stream;
//use Prewk\XmlStringStreamer\Parser;

use GuzzleHttp\Client;
use Bahjaat\Daisycon\Models\Data;
use Bahjaat\Daisycon\Repository\DataImportInterface;


class XmlDataImport implements DataImportInterface {

    /**
     * Importeer data van betreffende feed (url) in de database
     *
     * @param $url
     * @param $program_id
     * @param $feed_id
     * @param $custom_categorie
     */
    public function importData($url, $program_id, $feed_id, $custom_categorie)
    {
        $client = new Client();
        $response = $client->request('GET', $url, ['timeout' => 3]);
        $body = $response->getBody();
        $simpleXmlString = simplexml_load_string($body, null, LIBXML_NOCDATA ); // LIBXML_NOCDATA-trick from: http://dissectionbydavid.wordpress.com/2013/01/25/simple-simplexml-to-array-in-php/
        foreach ($simpleXmlString as $simpleXmlNode) :
            // Lege values eruit filteren
            $arr = array_filter(
                (array) $simpleXmlNode
            );
            try {
                 // Merge 'program_id' in gegevens uit XML
                $inserted_array = array_merge($arr,
                    array(
                        'program_id' => $program_id,
                        'feed_id' => $feed_id,
                        'custom_categorie' => $custom_categorie
                    )
                );
                Data::create(
                    $inserted_array
                );
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        endforeach;

        return;
    }
}