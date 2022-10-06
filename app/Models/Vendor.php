<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public $translatable = ['name'];

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function get_list_days() {
        return $this->hasMany(VendorListPackages::class)->where('status', '1');
    }

    public function timeDelivery() {
        return $this->hasMany(DeliveryTime::class, 'vendor_id')->where('hours', '<>', null);
    }
    
    public function timeDeliverySchaduling() {
        return $this->hasMany(DeliveryTime::class, 'vendor_id')->whereDate('created_at', Carbon::today());
    }

    public function days() {
       $start = $this->get_list_days();
       return $start->date_from;
    }

    public function pacakges() {
        return $this->hasMany(VendorListPackages::class, 'vendor_id');
    }

    public function drivers() {
        return $this->hasMany(Drivers::class, 'vendor_id');
    }

    public function package_hours() {
        return $this->hasOne(VendorListPackages::class, 'vendor_id')
            ->where('status', '1')
            ->where('number_of_order', null);
    }
    
    public function package_order() {
        return $this->hasOne(VendorListPackages::class, 'vendor_id')
            ->where('status', '1')
            ->where('number_of_order', '<>', null);
    }
    
    public function orders() {
        return $this->hasMany(Order::class, 'vendor_id');
    }
}
