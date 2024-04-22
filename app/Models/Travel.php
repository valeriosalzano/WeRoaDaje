<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Travel extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    
    protected $table = 'travels';
    public $incrementing = false;

    protected $fillable = [
        'isPublic',
        'slug',
        'name',
        'description',
        'numberOfDays',
        'image'
    ];

    public function tours() {
        return $this->hasMany(Tour::class,'travelId');
    }

    public function mood()
    {
        return $this->hasOne(Mood::class,'travelId');

    }
}
