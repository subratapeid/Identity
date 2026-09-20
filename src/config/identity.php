<?php

return [

    'name' => 'Pagelyne Identity',

    'version' => '1.0.0',

    'contexts' => [

        'admin' => [
            'guard' => 'web',
            'login_route' => 'admin.login',
        ],

        'customer' => [
            'guard' => 'customer',
            'login_route' => 'customer.login',
        ],

        'agent' => [
            'guard' => 'agent',
            'login_route' => 'agent.login',
        ],

    ],

];