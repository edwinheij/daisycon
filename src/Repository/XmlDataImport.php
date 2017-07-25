<?php

namespace Bahjaat\Daisycon\Repository;

//use Prewk\XmlStringStreamer;
//use Prewk\XmlStringStreamer\Stream;
//use Prewk\XmlStringStreamer\Parser;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

use Config;
use GuzzleHttp\Client;
use Bahjaat\Daisycon\Models\Data;
use Bahjaat\Daisycon\Repository\DataImportInterface;


class XmlDataImport implements DataImportInterface {

    public function importData($url, $program_id, $feed_id, $custom_category)
    {
//        $url = "http://example.com/really-large-xml-file.xml";

        $CHUNK_SIZE = 1024;
        $stream = new Stream\Guzzle($url, $CHUNK_SIZE);
        $parser = new Parser\StringWalker([
            "extractContainer" => true,
        ]);

//        $file = $url;
//        $head = array_change_key_case(get_headers($url, TRUE));
//        dd($head);
//        $totalSize = $head['content-length'];

//        $totalSize = filesize($file);

// Construct the file stream
//        $stream = new \File($file, 16384, function($chunk, $readBytes) use ($totalSize) {
//             This closure will be called every time the streamer requests a new chunk of data from the XML file
//            echo "Progress: $readBytes / $totalSize\n";
//        });

        $streamer = new XmlStringStreamer($parser, $stream);

        // Get the containing XML
//        $containingXml = $parser->getExtractedContainer();
//dd($containingXml);
//        $xmlObj = simplexml_load_string($containingXml);
//        $rootElementName = $xmlObj->getName();
//        $rootElementFooAttribute = $xmlObj->attributes()->foo;
//        echo $rootElementName;
//        echo $rootElementFooAttribute;
//        dd();
        while ($node = $streamer->getNode()) {

            $xml = simplexml_load_string($node);
            $rootNode = $xml->getName();

            switch ($rootNode) {
                case "info":
//                    var_dump((string)$xml->xpath('/info/category')[0]);
//                    var_dump((string)$xml->xpath('/info/sub_category')[0]);
                    var_dump($xml->asXml());

                break;
                case "programs":
                    echo ($rootNode);
//                    var_dump($xml->asXml());
//                    var_dump($xml->xpath('/programs/program/program_info'));
                    dd($xml->asXml());

                    break;
                default:
                    echo ($rootNode);
                    dd($xml->asXml());
            }
        }
        dd('stop');
    }

    /**
     * Importeer data van betreffende feed (url) in de database
     *
     * @param $url
     * @param $program_id
     * @param $feed_id
     * @param $custom_category
     */
    public function importData2($url, $program_id, $feed_id, $custom_category)
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