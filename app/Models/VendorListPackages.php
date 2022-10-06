<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorListPackages extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function driver() {
        return $this->hasMany(DeliveryTime::class, 'vendor_list_package_id');
    }
}
