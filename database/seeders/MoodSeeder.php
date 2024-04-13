<?php

namespace Database\Seeders;

use App\Models\Mood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
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

        foreach ($travels as $travel) {
            $mood = new Mood();
            $mood->travelId = $travel->id;
            $mood->nature = $travel->moods->nature;
            $mood->relax = $travel->moods->relax;
            $mood->history = $travel->moods->history;
            $mood->culture = $travel->moods->culture;
            $mood->party = $travel->moods->party;
            $mood->save();
        }
    }
}