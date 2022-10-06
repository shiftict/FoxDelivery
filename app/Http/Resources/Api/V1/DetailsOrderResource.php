<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailsOrderResource extends JsonResource
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
            's_id' => '#' . $this->id,
            's_name_customer' => $this->name,
            's_type_order' => $this->type_order == '0' ? __('order.oneWay') : __('order.twoWay'),
            's_mobile' => $this->phone,
            's_delivery_address' => $this->to_address, // only address customer
            's_delivery_lat' => $this->lat_to,
            's_delivery_long' => $this->long_to,
            's_receiving_address' => $this->from_address, // only address vendor
            's_receiving_lat' => $this->lat_from,
            's_receiving_long' => $this->long_from,
            's_status' => $this->statusApi->name,
            's_status_color' => $this->statusApi->color,
            'i_status' => $this->statusApi->id,
            's_description' => $this->description,
            's_order_reference' => $this->order_reference,
            's_block' => $this->block,
            's_home' => $this->home,
            's_street' => $this->street,
            's_avenue' => $this->sabil,
            'date_from' => $this->date_from,
            'time_from' => $this->time_from,
            'date_to' => $this->date_to,
            'time_to' => $this->time_to,
        ];
    }
}
