<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class StatusSystem extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public $translatable = ['name'];

    protected $guarded = [];

    protected $table = 'order_status';

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}

