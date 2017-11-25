<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class AccommodationNameFixer implements Fixer
{
    protected $data;

    public function fix($data)
    {
        $this->data = $data;

        $this->trimDoubleQuotes();
        $this->removeSku();

        return $this->data;
    }

    protected function removeSku() {
        $sku = $this->data['sku'];

        $accommodation_name = $this->data['accommodation_name'];

        $split = explode(' ', $accommodation_name);

        if (preg_match('/' . $sku . '/i', $accommodation_name)) {
            array_pop($split);
            $accommodation_name = implode(' ', $split);
        }

        $this->data['accommodation_name'] = $accommodation_name;
    }

    protected function trimDoubleQuotes()
    {
        $this->data['accommodation_name'] = trim($this->data['accommodation_name'], '"');
    }

}