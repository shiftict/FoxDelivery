<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $guarded = [];
    protected $with = 'area';

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public $translatable = ['name'];

    public function area() {
        return $this->belongsTo(Area::class, 'area_id');
    }
    
    public function getNameAttribute($value) {
        $jr = json_decode($value);
        return object_get($jr, app()->getLocale());
        // return get_object($value, app()->getLocale());
    }
    // public function setNameAttribute($value)
    // {
    //     return $this->name = app()->getLocale() == 'ar' ? $this->name['ar'] : $this->name['en'];//$value->getTranslation('name', 'en');
    // }
}
