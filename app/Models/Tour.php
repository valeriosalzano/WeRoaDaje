<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

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

    static function byTravelSlug($query, $slug)
    {
        return $query->join('travels', 'tours.travelId','travels.id')->where('travels.slug',$slug);
    }
    // PRICE FILTERS
    static function priceBetween($query, $minPrice = 0, $maxPrice = 1000000000)
    {
        return $query->whereBetween('price', [$minPrice*100, $maxPrice*100]);
    }

    static function orderByPrice($query,$order)
    {
        return $query->orderBy('price',$order);
    }

    // DATE FILTERS
    static function dateFrom($query, $dateFrom)
    {
        return $query->where('starting_date', '>=', $dateFrom);
    }

    static function dateTo($query, $dateTo)
    {
        return $query->where('starting_date', '<=', $dateTo);
    }

}
