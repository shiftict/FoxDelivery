<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;
use DB;
class Drivers extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'drivers';

    public function vendor() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_drivers_id');
    }

    public function drivers() {
        return $this->belongsTo(Delivery::class, 'deliveries_id');
        //->whereDate('created_at', Carbon::today());
    }
}
