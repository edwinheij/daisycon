<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class TravelTripTypeFixer implements Fixer
{
    protected $data;

    public function fix($data)
    {
        $this->data = $data;

        $this->ConvertToFullType();

        return $this->data;
    }

    protected function ConvertToFullType(): void
    {
        $ttt = $newType = $this->data['travel_trip_type'];

        switch (strtoupper($ttt)) {
            case "AI":
                $newType = 'All Inclusive';
                break;
            case "HP":
                $newType = 'Halfpension';
                break;
            case "LG":
                $newType = 'Logies';
                break;
            case "LO":
                $newType = 'Logies en ontbijt';
                break;
            case "VB":
                $newType = null;
                break;
            case "VP":
                $newType = 'Volpension';
                break;
            default:
                // stay as it is
        }

        $this->data['travel_trip_type'] = $newType;
    }

}