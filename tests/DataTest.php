<?php

namespace Bahjaat\Daisycon\Tests;

// Todo

use Bahjaat\Daisycon\Models\Data;

/**
 * Class DataTest
 * @coversDefaultClass Bahjaat\Daisycon\Models\Data
 */
class DataTest extends TestCase
{
    protected $data;

    protected $testdata;

    public function setUp()
    {
        parent::setUp();
        $this->data = new Data;
        $this->setupTestdata();
    }


    /**
     * Setup some test data
     * @coversNothing
     */
    protected function setupTestdata()
    {
        $testData = new stdClass();
        $testData->transportation_type = 'EV';
        $testData->board_type = 'LO';
        $testData->country_of_destination = 'DE';
        $testData->country_of_origin = 'BE';
        $testData->stars = 2;
        $testData->accommodation_name = 'New Accommodationname';
        $testData->title = 'New title';
        $testData->description = 'Luras sunt toruss de raptus devirginato. All abstruse doers absorb each other, only special followers have a zen. Modification, galaxy, and disconnection.';
        $testData->latitude = '1.12345';
        $testData->longitude = '1.12345';

        $testData->duration = 10;
        $testData->departure_date = (new \DateTime('now'))->format('Y/m/d');
        $interval = (new \DateInterval('P10D'));
//        dd($interval);
        $testData->end_date = (new \DateTime($testData->departure_date))
                ->add($interval)->format('Y/m/d');
//dd($testData->departure_date);
//dd($testData->end_date);
        $this->testdata = $testData;
    }

    /**
     * @test Fillable fields should exist. This has to be tested because they are set dynamically
     */
    public function test_fillable_fields_should_exist() {
        $dataFillableFields = $this->data->getFillable();
        return $this->assertGreaterThan(0, count($dataFillableFields));
    }

    /**
     * @test Test the converting of the transportation type
     * @covers ::fixTransportationType
     */
    public function test_transportation_type_is_changed()
    {
        $output = $this->data->fixTransportationType($this->testdata);
        $this->assertEquals($output->transportation_type, 'Eigen vervoer');
    }

    /**
     * @covers ::fixBoardingType
     * @test Test the converting of the boarding type
     */
    public function test_boarding_type_is_changed()
    {
        $output = $this->data->fixBoardingType($this->testdata);
        $this->assertEquals($output->board_type, 'Logies en ontbijt');
    }

    /**
     * @covers ::fixLandcodes
     * @test Test the converting of the landcodes
     */
    public function test_landcode_is_changed()
    {
        $output = $this->data->fixLandcodes($this->testdata);
        $this->assertEquals($output->country_of_destination, 'Duitsland');
        $this->assertEquals($output->country_of_origin, 'België');

        $this->testdata->country_of_origin = 'AA';
        $output = $this->data->fixLandcodes($this->testdata);

    }

    /**
     * @covers ::fixStars
     * @test Test the converting of the stars
     */
    public function test_stars_is_changed()
    {
        $output = $this->data->fixStars($this->testdata);
        $this->assertEquals($output->stars, 2);

        $this->testdata->stars = 0;
        $output = $this->data->fixStars($this->testdata);
        $this->assertEquals($output->stars, '');

        $this->testdata->stars = 'a';
        $output = $this->data->fixStars($this->testdata);
        $this->assertEquals($output->stars, '');

        $this->testdata->stars = 'a3';
        $output = $this->data->fixStars($this->testdata);
        $this->assertEquals($output->stars, 3);
    }

    /**
     * @covers ::fixAccommodationName
     * @test Test the accommodationname
     */
    public function test_accommodationname_is_changed()
    {
        $output = $this->data->fixAccommodationName($this->testdata);
        $this->assertEquals($output->accommodation_name, $this->testdata->accommodation_name);

        $this->testdata->accommodation_name = '';
        $output = $this->data->fixAccommodationName($this->testdata);
        $this->assertEquals($output->accommodation_name, $this->testdata->title);
    }

    /**
     * @covers ::fixDescription
     * @test Test the description
     */
    public function test_description_is_changed()
    {
        $output = $this->data->fixDescription($this->testdata);
        $this->assertEquals($output->description, $this->testdata->description);

        $this->testdata->description .= '...';
        $output = $this->data->fixDescription($this->testdata);
        $this->assertEquals($output->description, $this->testdata->description);

        $desc = $this->testdata->description;
        $this->testdata->description .= ', asdf...';
        $output = $this->data->fixDescription($this->testdata);
        $this->assertEquals($output->description, $desc . '.');

        $this->testdata->description = '';
        $output = $this->data->fixDescription($this->testdata);
        $this->assertEquals($output->description, $this->testdata->title);

        $this->testdata->description = 'A row without a period on the end of the line';
        $output = $this->data->fixDescription($this->testdata);
        $this->assertEquals($output->description, $this->testdata->description . '.');
    }
/**
 * @covers ::fixPositions
 * @test Test the position (location)
 */
    public function test_position_is_fixed()
    {
        $output = $this->data->fixPositions($this->testdata);
        $this->assertEquals($output->latitude, $this->testdata->latitude);
        $this->assertEquals($output->longitude, $this->testdata->latitude);

        $this->testdata->latitude = '0.00000';
        $output = $this->data->fixPositions($this->testdata);
        $this->assertEquals($output->latitude, '');
//        $this->assertEquals($output->longitude, '');

        $this->testdata->latitude = '';
        $output = $this->data->fixPositions($this->testdata);
        $this->assertEquals($output->latitude, '');
//        $this->assertEquals($output->longitude, '');

        $this->testdata->longitude = '0.00000';
        $output = $this->data->fixPositions($this->testdata);
//        $this->assertEquals($output->longitude, '');
        $this->assertEquals($output->longitude, '');

        $this->testdata->longitude = '';
        $output = $this->data->fixPositions($this->testdata);
        $this->assertEquals($output->longitude, '');
//        $this->assertEquals($output->longitude, '');
    }

    /**
     * @covers ::fixDuration
     * @test Test the duration
     */
    public function test_duration_is_changed()
    {
        $output = $this->data->fixDuration($this->testdata);
        $this->assertEquals($output->duration, $this->testdata->duration);

        $this->testdata->duration = 'a';
        $output = $this->data->fixDuration($this->testdata);
        $this->assertEquals(10, $output->duration);

        $this->testdata->duration = 'a';
        unset($this->testdata->departure_date);
        unset($this->testdata->end_date);
        $output = $this->data->fixDuration($this->testdata);
        $this->assertEquals('', $output->duration);
//var_dump($this->data->departure_date);
//var_dump($this->data->end_date);
//        die();
//        $this->testdata->duration = '';
//        $output = $this->data->fixDuration($this->testdata);
//        $this->assertEquals(6, $output->duration);
    }


}
