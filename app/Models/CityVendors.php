<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CityVendors extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }
    
    public function user() {
        return $this->belongsTo(City::class, 'user_id');
    }
}
