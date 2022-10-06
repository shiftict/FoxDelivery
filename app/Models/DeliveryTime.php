<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryTime extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $appends = ['driver'];

    public function getDriverAttribute() {
        return $this->Driver()->first();
    }

    public function Driver() {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }
}
