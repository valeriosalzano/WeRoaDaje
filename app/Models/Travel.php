<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;
    use HasUuids;
    
    protected $table = 'travels';
     
    protected $fillable = [
        'visible',
        'slug',
        'name',
        'description',
        'numberOfDays',
        'image'
    ];

    public function tours() {
        return $this->hasMany(Tour::class);
    }

    public function mood()
    {
        return $this->hasOne(Mood::class);

    }
}
