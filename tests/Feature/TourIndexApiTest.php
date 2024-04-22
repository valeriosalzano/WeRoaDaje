<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TourIndexApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** @test */
    public function test_get_list_of_tours_by_wrong_travel_slug()
    {
        $response = $this->get('/api/travels/foo-bar/tours');

        $response->assertStatus(404);

        $response->assertJson([
            'success' => false,
            'message' => 'Travel not found'
        ]);
    }

    /** @test */
    public function test_get_list_of_tours_with_correct_response_structure()
    {
        $response = $this->get('/api/tours');

        $response->assertOk();

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'code',
                        'firstDay',
                        'lastDay',
                        'price'
                    ]
                ],
                'links'
            ],
        ]);
    }

    /** @test */
    public function test_get_list_of_tours_with_correct_response_default_order()
    {
        $response = $this->get('/api/tours');
        $response->assertOk();
        $tours = $response->json('data.data');
        $sorted = collect($tours)->sortBy('firstDay')->values()->toArray();
        $this->assertEquals($sorted,$tours);
    }
    
    /** @test */
    public function test_get_list_of_tours_ordered_by_price()
    {
        //ASC
        $responseAsc = $this->get('/api/tours?orderByPrice=asc');
        $responseAsc->assertOk();
        $toursAsc = $responseAsc->json('data.data');

        $sortedAsc = collect($toursAsc)->sortBy('price',SORT_NUMERIC,false)->values()->toArray();
        $this->assertEquals($sortedAsc,$toursAsc);

        //DSC
        $responseDesc = $this->get('/api/tours?orderByPrice=desc');
        $responseDesc->assertOk();
        $toursDesc = $responseDesc->json('data.data');

        $sortedDesc = collect($toursDesc)->sortBy('price',SORT_NUMERIC,true)->values()->toArray();
        $this->assertEquals($sortedDesc,$toursDesc);
    }

    /** @test */
    public function test_get_list_of_tours_with_price_filters()
    {
        $priceFrom = 1900;
        $priceTo = 2000;

        $response = $this->get("/api/tours?priceFrom=$priceFrom&priceTo=$priceTo");
        $response->assertOk();
        $tours = $response->json('data.data');

        foreach ($tours as $tour) {
            $this->assertGreaterThanOrEqual($priceFrom,$tour['price']);
            $this->assertLessThanOrEqual($priceTo,$tour['price']);
        }
    }

    /** @test */
    public function test_get_list_of_tours_with_date_filters()
    {
        $dateFrom = "2021-11-01";
        $dateTo = "2021-11-12";

        $response = $this->get("/api/tours?dateFrom=$dateFrom&dateTo=$dateTo");
        $response->assertOk();
        $tours = $response->json('data.data');

        foreach ($tours as $tour) {
            $this->assertTrue(strtotime($tour['firstDay']) >= strtotime($dateFrom));
            $this->assertTrue(strtotime($tour['firstDay']) <= strtotime($dateTo));
        }   
    }
}
