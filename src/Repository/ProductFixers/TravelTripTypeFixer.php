<?php

namespace Bahjaat\Daisycon\Repository\ProductFixers;

class TravelTripTypeFixer implements Fixer
{
    protected $model;

    public function handle($model)
    {
        $this->model = $model;

        $this->fix();
    }

    protected function fix()
    {
        $ttt = $newType = $this->model->travel_trip_type;

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

        $this->model->travel_trip_type = $newType;
    }

}