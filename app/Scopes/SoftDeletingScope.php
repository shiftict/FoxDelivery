<?php
/**
 * Created by PhpStorm.
 * User: MKD
 * Date: 3/9/20
 * Time: 6:53 PM
 */

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SoftDeletingScope extends \Illuminate\Database\Eloquent\SoftDeletingScope
{
    
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // TODO: add user role check
        $builder->whereNull($model->getQualifiedDeletedAtColumn());
    }

}
