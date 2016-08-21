<?php

use Bahjaat\Daisycon\Models\Data;

class DataTest extends \TestCase
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
     */
    protected function setupTestdata()
    {
        $testData = new stdClass();
        $testData->transportation_type = 'EV';

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
     */
    public function test_transportation_type_is_changed()
    {
        $output = $this->data->fixTransportationType($this->testdata);
        $this->assertEquals($output->transportation_type, 'Eigen vervoer');
    }

}
