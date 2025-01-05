<?php

return [
    [
        'permission' => 'r_user',
        'activeRoutes' => [
            'user/user/admin/index', 'user/user/admin/create',
            'user/user/admin/update', 'user/user/admin/archives',
        ],
        'route' => '/user',
        'content' => '<i class="nav-icon fa fa-user"></i> <p>Пользователи</p>',
    ],
    [
        'permission' => ['developer', 'admin'],
        'activeRoutes' => [
            'references/standard/index', 'references/standard/create', 'references/standard/update',
            'references/hardness/index', 'references/hardness/create', 'references/hardness/update',
            'references/outer-diameter/index', 'references/outer-diameter/create', 'references/outer-diameter/update',
            'references/control-method/index', 'references/control-method/create', 'references/control-method/update',
            'references/nd-control-method/index', 'references/nd-control-method/create', 'references/nd-control-method/update',
            'references/control-result/index', 'references/control-result/create', 'references/control-result/update',
            'references/mass-fraction/index', 'references/mass-fraction/create', 'references/mass-fraction/update',
            'references/wall-thickness/index', 'references/wall-thickness/create', 'references/wall-thickness/update',
            'references/theoretical-mass/index', 'references/theoretical-mass/create', 'references/theoretical-mass/update',
            'references/test-pressure/index', 'references/test-pressure/create', 'references/test-pressure/update',
            'references/relative-extension/index', 'references/relative-extension/create', 'references/relative-extension/update',
            'references/hardness-limit/index', 'references/hardness-limit/create', 'references/hardness-limit/update',
            'references/strength-limit/index', 'references/strength-limit/create', 'references/strength-limit/update',
            'references/fluidity-limit/index', 'references/fluidity-limit/create', 'references/fluidity-limit/update',
            'references/absorbed-energy-limit/index', 'references/absorbed-limit/create',
            'references/absorbed-energy-limit/update',
            'references/customer/index', 'references/customer/update',
        ],
        'route' => '#',
        'content' => '<i class="nav-icon fa fa-bars"></i> <p>Справочники<i class="right fas fa-angle-left"></i></p>',
        'subItems' => [
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/standard/index', 'references/standard/create',
                    'references/standard/update',
                ],
                'route' => '/references/standard',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Стандарт</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/hardness/index', 'references/hardness/create',
                    'references/hardness/update',
                ],
                'route' => '/references/hardness',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Группа прочности</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/outer-diameter/index', 'references/outer-diameter/create',
                    'references/outer-diameter/update',
                ],
                'route' => '/references/outer-diameter',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Наружний диаметр</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/control-method/index', 'references/control-method/create',
                    'references/control-method/update',
                ],
                'route' => '/references/control-method',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Метод контроля</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/nd-control-method/index', 'references/nd-control-method/create',
                    'references/nd-control-method/update',
                ],
                'route' => '/references/nd-control-method',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>НД на контроль</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/control-result/index', 'references/control-result/create',
                    'references/control-result/update',
                ],
                'route' => '/references/control-result',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Результат контроля</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/mass-fraction/index', 'references/mass-fraction/create',
                    'references/mass-fraction/update',
                ],
                'route' => '/references/mass-fraction',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Массовая доля элементов</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/wall-thickness/index', 'references/wall-thickness/create',
                    'references/wall-thickness/update',
                ],
                'route' => '/references/wall-thickness',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Толщина стенки</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/theoretical-mass/index', 'references/theoretical-mass/create',
                    'references/theoretical-mass/update',
                ],
                'route' => '/references/theoretical-mass',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Теоретическая масса</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/test-pressure/index', 'references/test-pressure/create',
                    'references/test-pressure/update',
                ],
                'route' => '/references/test-pressure',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Испытательное давление</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/relative-extension/index', 'references/relative-extension/create',
                    'references/relative-extension/update',
                ],
                'route' => '/references/relative-extension',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Относительное удлинение</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/hardness-limit/index', 'references/hardness-limit/create',
                    'references/hardness-limit/update',
                ],
                'route' => '/references/hardness-limit',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Предел твердости</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/strength-limit/index', 'references/strength-limit/create',
                    'references/strength-limit/update',
                ],
                'route' => '/references/strength-limit',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Предел прочности</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/fluidity-limit/index', 'references/fluidity-limit/create',
                    'references/fluidity-limit/update',
                ],
                'route' => '/references/fluidity-limit',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Предел текучести</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/absorbed-energy-limit/index', 'references/absorbed-energy-limit/create',
                    'references/absorbed-energy-limit/update',
                ],
                'route' => '/references/absorbed-energy-limit',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Поглощенная энергия</p>',
            ],
            [
                'permission' => ['admin', 'developer'],
                'activeRoutes' => [
                    'references/customer/index', 'references/customer/update',
                ],
                'route' => '/references/customer',
                'content' => '<i class="nav-icon far fa-circle"></i> <p>Покупатели</p>',
            ],
        ]
    ],
    [
        'permission' => 'r_settings',
        'activeRoutes' => ['settings/default/index', 'settings/default/update'],
        'route' => '/settings',
        'content' => '<i class="nav-icon fa fa-cogs"></i> <p>Настройки</p>',
    ],
];
