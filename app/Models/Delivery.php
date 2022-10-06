<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $appends = ['StartTime', 'endTime'];
    protected $with = ['users'];

    public function methodShipping() {
        return $this->belongsTo(MethodShipping::class, 'method_id');
    }

    public function timeDelivery() {
        return $this->hasMany(DeliveryTime::class, 'delivery_id');
    }

    public function getstartTimeAttribute() {
//        return $this->startTime()->get();
    }

    public function getendTimeAttribute() {
//        return $this->endTime()->get();
    }

    public function startTime() {
        return $this->hasMany(DeliveryTime::class, 'delivery_id');
    }

    public function endTime() {
        return $this->hasMany(DeliveryTime::class, 'delivery_id');
    }

    public function pendingOrder() {
        return $this->hasMany(Order::class, 'delivery_id')->whereIn('order_status', [1,2,3,6])->orderBy('id', 'desc');
    }

    public function allOrder() {
        return $this->hasMany(Order::class, 'delivery_id')->whereIn('order_status', [4,5,7])->orderBy('id', 'desc');
    }

    public function driver_notification() {
        return $this->hasMany(NotificationDriver::class, 'driver_id')->orderBy('id', 'desc');
    }
    
    public function active_order() {
        return $this->hasMany(Order::class, 'delivery_id')->whereIn('order_status', [2, 3, 6]);
    }
    
    public function acitve_driver() {
        return $this->belongsTo(User::class, 'user_id')->where('is_online', '1');
    }
    
    public function users() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /*public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }*/
}
