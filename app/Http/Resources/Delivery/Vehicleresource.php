<?php

namespace App\Http\Resources\Delivery;

use Illuminate\Http\Resources\Json\JsonResource;

class Vehicleresource extends JsonResource
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
            'vehicle' => $this->vehicle,
            'hours' => $this->hours,
        ];
    }
}
