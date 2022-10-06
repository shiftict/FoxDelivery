<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasFactory, HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'lat',
        'long',
        'address',
        'vendor_id',
        'tokenfcm',
        'password',
        'mobile',
        'code',
        'is_online',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function store() {
        return $this->hasOne(Vendor::class, 'user_id')->where('status', '1');
    }

    public function stores() {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    public function package_hours() {
        return $this->hasOne(VendorListPackages::class, 'user_id')
            ->where('status', '1')
            ->where('number_of_order', null);
    }

    public function package_orders() {
        return $this->hasOne(VendorListPackages::class, 'user_id')
            ->where('status', '1')
            ->where('number_of_order', '<>', null);
    }

    public function packages() {
        return $this->hasOne(VendorListPackages::class, 'user_id')->where('status', '1');
    }

    public function packages_new() {
        return $this->hasMany(VendorListPackages::class, 'user_id')->where('status', '1');
    }

    public function packagesWithExpierd() {
        return $this->hasMany(VendorListPackages::class, 'user_id');
    }

    public function delivery() {
        return $this->hasOne(Delivery::class, 'user_id');
    }

    public function city() {
        $city = $this->hasMany(CityVendors::class, 'user_id');
        return $city;
    }

    public function routeNotificationForFcm()
    {
        return $this->tokenfcm;
    }
}
