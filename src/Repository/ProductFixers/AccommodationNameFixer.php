<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class AccommodationNameFixer implements Fixer
{
    protected $model;

    public function handle($model)
    {
        $this->model = $model;

        $this->trimDoubleQuotes();
        $this->removeSku();
    }

    protected function removeSku() {
        $sku = $this->model->sku;

        $accommodation_name = $this->model->accommodation_name;

        $split = explode(' ', $accommodation_name);

        if (preg_match('/' . $sku . '/i', $accommodation_name)) {
            array_pop($split);
            $accommodation_name = implode(' ', $split);
        }

        $this->model->accommodation_name = $accommodation_name;
    }

    protected function trimDoubleQuotes()
    {
        $this->model->accommodation_name = trim($this->model->accommodation_name, '"');
    }
}