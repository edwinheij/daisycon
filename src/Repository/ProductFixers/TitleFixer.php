<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class TitleFixer implements Fixer
{
    protected $data;

    public function fix($data)
    {
        $this->data = $data;

        $this->fillFromTitleWhenEmpty();
        return $this->data;
    }

    protected function fillFromTitleWhenEmpty()
    {
        if (!isset($this->data['accommodation_name']) && isset($this->data['title'])) {
            if (isset($this->data['airport_departure']) && isset($this->data['airport_destination'])) {
                $this->data['title'] = implode(' ', [
                    $this->data['airport_departure'],
                    '-',
                    $this->data['airport_destination']
                ]);
            }
        }
    }
}