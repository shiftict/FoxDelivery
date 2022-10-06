<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationDriver extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'notification_driver_orders';
    protected $appends = ['order', 'user', 'delivery'];

    public function delivery() {
        return $this->belongsTo(Delivery::class, 'driver_id');
    }

    public function getDeliveryAttribute() {
        return $this->delivery()->first();
    }

    public function user() {
        return $this->belongsTo(StatusSystem::class, 'user_id');
    }

    public function getUserAttribute() {
        return $this->delivery()->first();
    }

    public function order() {
        return $this->belongsTo(StatusSystem::class, 'order_id');
    }

    public function getOrderAttribute() {
        return $this->delivery()->first();
    }
}
