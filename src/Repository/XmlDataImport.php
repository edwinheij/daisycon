<?php

namespace Bahjaat\Daisycon\Repository;

//use Prewk\XmlStringStreamer;
//use Prewk\XmlStringStreamer\Stream;
//use Prewk\XmlStringStreamer\Parser;

use Config;
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
     * @param $custom_category
     */
    public function importData($url, $program_id, $feed_id, $custom_category)
    {
        $client = new Client;
        $response = $client->request('GET', $url, [
            'headers' => ['Accept' => 'application/xml'],
            'timeout' => 120
        ]);
        $body = $response->getBody()->getContents();
        $simpleXmlString = simplexml_load_string($body, null, LIBXML_NOCDATA ); // LIBXML_NOCDATA-trick from: http://dissectionbydavid.wordpress.com/2013/01/25/simple-simplexml-to-array-in-php/
        if ($simpleXmlString instanceof \SimpleXMLElement) :

        $products = $simpleXmlString->xpath('/datafeed/programs/program/products/*/product_info');

        foreach ($products as $simpleXmlNode) {
            $arr = array_filter(
                (array)$simpleXmlNode
            );
//            dd($arr);
            try {
                // Merge 'program_id' in gegevens uit XML
                $inserted_array = array_merge($arr,
                    array(
                        'program_id'       => $program_id,
                        'feed_id'          => $feed_id,
                        'custom_category' => $custom_category
                    )
                );
//                dd($inserted_array);
                Data::create(
                    $inserted_array
                );
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        }

        endif;

        return;
    }
}