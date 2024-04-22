<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'numberOfDays' => $this->numberOfDays,
            'numberOfNights' => $this->numberOfDays >= 1 ? $this->numberOfDays - 1 : 0,
            'image' => $this->image,
            'moods' => [
                'nature' => $this->mood->nature,
                'relax' => $this->mood->relax,
                'history' => $this->mood->history,
                'culture' => $this->mood->culture,
                'party' => $this->mood->party
            ]
        ];
    }
}
