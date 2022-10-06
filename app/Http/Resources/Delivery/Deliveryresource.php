<?php

namespace App\Http\Resources\Delivery;

use Illuminate\Http\Resources\Json\JsonResource;

class Deliveryresource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'lat' => $this->acitve_driver->last_lat_driver,
            'lng' => $this->acitve_driver->last_long_driver,
        ];
    }
}
