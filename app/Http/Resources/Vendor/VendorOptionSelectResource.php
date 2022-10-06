<?php

namespace App\Http\Resources\Vendor;

use App\Http\Resources\Packages\PackagesResource;
use App\Http\Resources\TimeDelivery\TimeDeliveryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorOptionSelectResource extends JsonResource
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
            'nameAr' => $this->name,
        ];
    }
}
