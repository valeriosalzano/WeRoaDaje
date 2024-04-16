<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'travelId';
    public $incrementing = false;
    
    protected $fillable = [
        'travelId',
        'nature',
        'relax',
        'history',
        'culture',
        'party'
    ];

    public function travel()
    {
        return $this->belongsTo(Travel::class,'travelId');
    }
}
