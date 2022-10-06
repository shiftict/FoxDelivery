<?php

namespace App\Http\Resources\Api\V1;

use App\Models\NotificationDriver;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            's_title' => __('api.assign_order') . ' #' . $this->order_id,
            's_read_at' => $this->read_at,
            's_date' => Carbon::parse($this->created_at)->format('Y-m-d'),
            's_time' => Carbon::parse($this->created_at)->format('H:i:m'),
            's_order_id' => (string)$this->order_id,
        ];
    }
}
