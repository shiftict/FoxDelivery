<?php

namespace App\Http\Resources\Packages;

use App\Http\Resources\Delivery\Vehicleresource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PackagesResource extends JsonResource
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
            'stock' => $this->number_of_order,
            'start' => Carbon::parse($this->date_from)->format('Y-m-d'),
            'end' => Carbon::parse($this->date_to)->format('Y-m-d'),
            'pricing' => $this->pricing,
            'driver_number' => $this->driver_number,
            'driver' => Vehicleresource::collection($this->driver),
            'vendor_id' => $this->vendor_id,
            'user_id' => $this->user_id,
        ];
    }
}
