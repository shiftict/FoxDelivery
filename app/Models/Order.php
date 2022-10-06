<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $appends = ['packages', 'status_api'];

    public function vendor() {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function getPackagesAttribute() {
        return $this->packages()->first();
    }

    public function packages() {
        return $this->belongsTo(VendorListPackages::class, 'vendor_list_package_id');
    }

    public function delivery() {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function statusOrder() {
        return $this->belongsTo(StatusSystem::class, 'order_status');
    }

    public function StatusApi() {
        return $this->belongsTo(StatusSystem::class, 'order_status');
    }

    public function getStatusApiAttribute() {
        return $this->StatusApi()->first();
    }
    
     public function format(){
        return [
          'id' => $this->id,
          'lat' => $this->delivery->users->last_lat_driver,
          'lng' => $this->delivery->users->last_long_driver,
          'driver' => $this->delivery->users->name,
          'id_driver' => $this->delivery->users->id
        ];
      }
      
    public function city() {
        return $this->belongsTo(Area::class, 'city_id');
    }
}
