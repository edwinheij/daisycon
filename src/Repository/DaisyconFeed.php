<?php

namespace Bahjaat\Daisycon\Repository;

use Exception;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser\StringWalker;
use Bahjaat\Daisycon\Models\Product;
use Bahjaat\Daisycon\Models\Productfeed;
use Bahjaat\Daisycon\Models\Productinfo;
use Prewk\XmlStringStreamer\Stream\Guzzle;

class DaisyconFeed
{
    protected $productfeed;

    /**
     * @param \Bahjaat\Daisycon\Models\Productfeed $productfeed
     *
     * @throws \Exception
     */
    public function import(Productfeed $productfeed)
    {
        if ($productfeed->program->status != 'active') {
            throw new Exception('Programma voor deze productfeed is niet actief');
        }

        $this->productfeed = $productfeed;

        try {
            echo $url = $productfeed->url;

            $CHUNK_SIZE = 1024;
            $stream     = new Guzzle($url, $CHUNK_SIZE);

            $options = [
                'captureDepth' => 2,
            ];

            $parser = new StringWalker($options);

            $streamer = new XmlStringStreamer($parser, $stream);

            while ($node = $streamer->getNode()) {
                $simpleXml = new \SimpleXMLElement($node, LIBXML_NOCDATA);

                $info = $simpleXml->xpath('//info');
                if ($info) {
                    $this->parseInfo($info, $productfeed);
                }

                $programs = $simpleXml->xpath('//programs/program');
                if ($programs) {
                    $this->parsePrograms($programs); // product model
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            \Log::error("Productfeed met id {$productfeed->id} failed met error: {$e->getMessage()}");
        }

        return;
    }

    /**
     * @param $programs
     */
    private function parsePrograms($programs)
    {
        foreach ($programs as $program) {
            $products = $program->products->product;
            if (count($products)) {
                $this->parseProducts($products);
            }
        }
    }

    /**
     * @param $info
     * @param $productfeed
     *
     * @return mixed
     */
    private function parseInfo($info, $productfeed)
    {
        $info                      = (array)$info[0];
        $additionalProductfeedData = array_filter($info);
        unset($additionalProductfeedData['product_count']);

        return $productfeed->update($additionalProductfeedData);
    }

    /**
     * @param $products
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function parseProducts($products)
    {
        foreach ($products as $product) {
            $product_info = (array)$product->product_info;
            $update_info  = (array)$product->update_info;

            $this->storeProduct($product_info, $update_info);
        }
    }

    /**
     * @param array $product_info
     * @param array $update_info
     *
     * @return \Illuminate\Database\Eloquent\Model|void
     */
    public function storeProduct(array $product_info, array $update_info)
    {
        $product_info['productfeed_id'] = $this->productfeed->id;
        $product_info['image']          = (string)$product_info['images']->image->location;

        $pi = Productinfo::updateOrCreate([
            'daisycon_unique_id' => $update_info['daisycon_unique_id']
        ], $update_info);

        $product = Product::updateOrCreate([
            'sku' => $product_info['sku']
        ], $product_info);
        $product->productinfo()->save($pi);
    }
}