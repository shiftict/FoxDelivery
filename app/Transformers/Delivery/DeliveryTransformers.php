<?php

namespace App\Transformers\Delivery;

use App\Models\Delivery;
use League\Fractal\TransformerAbstract;

class DeliveryTransformers extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Delivery $delivery)
    {
        return [
            //
            'id' => $delivery->id,
            'name' => $delivery->name,
        ];
    }
}
