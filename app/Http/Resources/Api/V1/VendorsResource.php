<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorsResource extends JsonResource
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
            'i_id' => $this->id,
            's_name' => $this->name,
            's_mobile' => $this->mobile,
            's_email' => $this->email,
            's_accessToken' => $this->accessToken,
            's_store_name' => $this->store->name,
            's_lat' => $this->store->lat,
            's_long' => $this->store->long,
            'i_count_order' => (int)$this->store->package_order->number_of_order,
        ];
    }
}
