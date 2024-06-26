<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'travelId',
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

    public function scopeByTravelId($query, $travelId)
    {
        return $query->where('travelId',$travelId);
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
