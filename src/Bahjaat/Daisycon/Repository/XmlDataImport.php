<?php
/**
 * Created by PhpStorm.
 * User: Edwin
 * Date: 30-1-2015
 * Time: 21:50
 */

namespace Bahjaat\Daisycon\Repository;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;

use Bahjaat\Daisycon\Models\Data;
use Bahjaat\Daisycon\Repository\DataImportInterface;


class XmlDataImport implements DataImportInterface {

    /**
     *
     */
    public function importData($url, $program_id, $feed_id, $custom_categorie)
    {

        $CHUNK_SIZE = 512;
        $stream = new Stream\Guzzle($url, $CHUNK_SIZE);
        $config = array(
            'uniqueNode' => 'item',
        );
        $parser = new Parser\UniqueNode($config);
        $streamer = new XmlStringStreamer($parser, $stream);

        while ($node = $streamer->getNode()) {

            $simpleXmlNode = simplexml_load_string($node, null, LIBXML_NOCDATA ); // LIBXML_NOCDATA-trick from: http://dissectionbydavid.wordpress.com/2013/01/25/simple-simplexml-to-array-in-php/

            /**
             * Lege values eruit filteren
             */
            $arr = array_filter(
                (array) $simpleXmlNode
            );

            try {
                /**
                 * Merge 'program_id' in gegevens uit XML
                 */
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

        } // while

        return;
    }
}