<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public $translatable = ['name'];

    protected $guarded = [];

    public function cities() {
        return $this->hasMany(City::class, 'area_id');
    }

    // soft delete at all city where are has city
    public static function boot() {
        parent::boot();
        static::deleting(function($area) { // before delete() method call this
            $area->cities()->delete();
            // do the rest of the cleanup...
        });
    }
}
