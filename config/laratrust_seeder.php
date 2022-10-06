<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadministrator' => [
            'users' => 'c,r,u,p',
            'area' => 'c,r,u,d',
            'city' => 'c,r,u,d',
            'vendor' => 'c,r,u,d',
            'order' => 'c,r,u,d',
            'delivery' => 'c,r,u,d',
            'package' => 'c,r,u,d',
            'method_shipping' => 'c,r,u,d',
            'report' => 'r',
            'profile' => 'r,u'
        ],
        'administrator' => [
            'users' => 'c,r,u',
            'area' => 'c,r,u,d',
            'city' => 'c,r,u,d',
            'vendor' => 'c,r,u,d',
            'order' => 'r,u,d',
            'delivery' => 'c,r,u,d',
            'method_shipping' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'vendor' => [
            'order' => 'c,r,u,d',
            'profile' => 'r,u',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'p' => 'permission'
    ]
];
