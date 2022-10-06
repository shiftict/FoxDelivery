<?php

namespace App\Transformers\MethodShipping;

use League\Fractal\TransformerAbstract;

class MethodShipping extends TransformerAbstract
{
    /**
     * @param $item
     *
     * @return array
     */
    public function transform($item)
    {
        return [
            'name'           => $item->code,
            'name'           => $item->name,
            'price_average'  => $item->price_average,
            'alert_quantity' => $item->alert_quantity,
            'description'    => $item->description,
            'qyt_in_store' => $item->roomItemsSumm(),
            'status'         => view('datatable.general.status', [
                'status' => $item->is_active, 'id' => $item->uuid,
                'api'    => '/api/v1/items/' . $item->uuid . '/status'
            ])->render(),
            'action'         => view('datatable.general.actions', [
                'id'      => $item->uuid,
                'api'     => '/api/v1/items',
                'url'     => '/items',
                'actions' => ['edit', 'delete']
            ])->render(),
        ];
    }
}
