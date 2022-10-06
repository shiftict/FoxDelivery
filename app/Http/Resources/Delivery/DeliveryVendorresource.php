<?php

namespace App\Http\Resources\Delivery;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryVendorresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'lat' => $this->user->last_lat_driver,
            'lng' => $this->user->last_long_driver,
            'start_first_shift' => $this->time_first_from ? $this->time_first_from : '-',
            'finish_first_shift' => $this->time_first_to ? $this->time_first_to : '-',
            'start_secound_shift' => $this->start_secound_shift ? $this->start_secound_shift : '-',
            'finish_secound_shift' => $this->end_secound_shift ? $this->end_secound_shift : '-',
        ];
    }
}
