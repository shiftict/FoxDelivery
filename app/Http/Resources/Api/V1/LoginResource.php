<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
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
            's_accessToken' => $this->accessToken,
            's_vehicle' => $this->delivery->methodShipping->name,
            's_lat' => $this->last_lat_driver,
            's_long' => $this->last_long_driver,
            's_status' => $this->is_online == '0' ? __('api.status_offline') : __('api.status_online'),
            'i_status' => (int)$this->is_online,
        ];
    }
}
