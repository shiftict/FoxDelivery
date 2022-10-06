<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    use HasTranslations;
    public $guarded = [];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public $translatable = ['display_name'];
}
