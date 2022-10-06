<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationVendorOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $append = ['code'];

    public function getCodeAttribute() {
        if($this->status == '0') {
            return __('notificationOrder.pending');
        } elseif($this->status == '1') {
            return __('notificationOrder.tripHasStarted');
        } elseif($this->status == '2') {
            return __('notificationOrder.received');
        } elseif($this->status == '3') {
            return __('notificationOrder.sentDeliveredHanded');
        } elseif($this->status == '4') {
            return __('notificationOrder.cancellation');
        }
    }

    public function statusOrder() {
        return $this->belongsTo(StatusSystem::class, 'status');
    }
}
