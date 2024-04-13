<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__ .'/tours.json');
        $tours = json_decode($json);

        foreach($tours as $tour_){
            $tour = new Tour();
            $tour->id = $tour_->id;
            $tour->travelId = $tour_->travelId;
            $tour->name = $tour_->name;
            $tour->startingDate = $tour_->startingDate;
            $tour->endingDate = $tour_->endingDate;
            $tour->price = $tour_->price;
            $tour->save();
        }
    }
}