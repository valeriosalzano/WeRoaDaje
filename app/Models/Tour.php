<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'startingDate',
        'endingDate',
        'price'
    ];

    public function getPriceAttribute($value){
        return $value / 100;
    }

    public function travel()
    {
        return $this->belongsTo(Travel::class,'travelId');
    }

    public function scopeByTravelSlug($query, $slug)
    {
        return $query->join('travels', 'tours.travelId','travels.id')->where('travels.slug',$slug);
    }

    // PRICE FILTERS
    public function scopePriceBetween($query, $minPrice = 0, $maxPrice = 1000000000)
    {
        return $query->whereBetween('price', [$minPrice*100, $maxPrice*100]);
    }

    public function scopeOrderByPrice($query,$order)
    {
        return $query->orderBy('price',$order);
    }

    // DATE FILTERS
    public function scopeDateFrom($query, $dateFrom)
    {
        return $query->where('startingDate', '>=', $dateFrom);
    }

    public function scopeDateTo($query, $dateTo)
    {
        return $query->where('startingDate', '<=', $dateTo);
    }

}
