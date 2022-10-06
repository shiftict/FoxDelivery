<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class MethodShipping extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public $translatable = ['name'];

    protected $guarded = [];

    public function delivery() {
        return $this->hasMany(Delivery::class, 'method_id');
    }

    // soft delete at all delivery where are has delivery
    public static function boot() {
        parent::boot();
        static::deleting(function($methodShipping) { // before delete() method call this
            $methodShipping->delivery()->delete();
            // do the rest of the cleanup...
        });
    }
}
