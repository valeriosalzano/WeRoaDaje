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
            'isPublic' => $this->deleted_at ? false : true,
            'name' => $this->name,
            'slug' => $this->slug,
            'name' => $this->name,
            'description' => $this->description,
            'numberOfDays' => $this->numberOfDays,
            'image' => $this->image,
            'moods' => $this->mood
        ];
    }
}
