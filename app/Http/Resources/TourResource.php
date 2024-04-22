<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
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
            'slug' => $this->travel->slug,
            'code' => $this->name,
            'firstDay' => $this->startingDate,
            'lastDay' => $this->endingDate,
            'price' => $this->price
        ];
    }
}
