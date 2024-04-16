<?php

namespace Database\Seeders;

use App\Models\Travel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = file_get_contents(__DIR__ .'/travels.json');
        $travels = json_decode($json);

        foreach($travels as $travel_){
            $travel = new Travel();
            $travel->visible = true;
            $travel->id = $travel_->id;
            $travel->name = $travel_->name;
            $travel->slug = $travel_->slug;
            $travel->description = $travel_->description;
            $travel->numberOfDays = $travel_->numberOfDays;
            $travel->save();
        }
    }
}