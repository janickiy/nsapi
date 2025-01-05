<?php
return [
    // роли
    [
        'name' => 'user',
        'type' => 1,
        'description' => 'Пользователь',
        'is_system' => false,
    ],
    [
        'name' => 'moderator',
        'type' => 1,
        'description' => 'Модератор',
        'is_system' => false,
    ],
    [
        'name' => 'developer',
        'type' => 1,
        'description' => 'Разработчик',
        'is_system' => false,
    ],
    [
        'name' => 'admin',
        'type' => 1,
        'description' => 'Администратор',
        'is_system' => false,
    ],
    [
        'name' => 'test_delete_role',
        'type' => 1,
        'description' => 'Тестовое удаление роли',
        'is_system' => false,
    ],


    // права
    [
        'name' => 'w_user',
        'type' => 2,
        'description' => 'Редактирование пользователей',
        'is_system' => false,
    ],
    [
        'name' => 'r_user',
        'type' => 2,
        'description' => 'Просмотр пользователей',
        'is_system' => false,
    ],
    [
        'name' => 'r_site',
        'type' => 2,
        'description' => 'Просмотр админки',
        'is_system' => false,
    ],
    [
        'name' => 'w_site',
        'type' => 2,
        'description' => 'Редактирование админки',
        'is_system' => false,
    ],
    [
        'name' => 'backend',
        'type' => 2,
        'description' => 'Доступ к админ панели',
        'is_system' => false,
    ],
    [
        'name' => 'wg_user_info',
        'type' => 2,
        'description' => 'Виджет инфрормации о пользователях модуля yii2-user',
        'is_system' => false,
    ],
    [
        'name' => 'wg_user_admin_info',
        'type' => 2,
        'description' => 'Виджет инфрормации об админах модуля yii2-user',
        'is_system' => false,
    ],
    [
        'name' => 'r_settings',
        'type' => 2,
        'description' => 'Просмотр настроек',
        'is_system' => false,
    ],
    [
        'name' => 'w_settings',
        'type' => 2,
        'description' => 'Редактирование настроек',
        'is_system' => false,
    ],
    [
        'name' => 'r_mailnotify',
        'type' => 2,
        'description' => 'Просмотр почтовых шаблонов',
        'is_system' => false,
    ],
    [
        'name' => 'w_mailnotify',
        'type' => 2,
        'description' => 'Редактирование почтовых шаблонов',
        'is_system' => false,
    ],
    [
        'name' => 'r_pages',
        'type' => 2,
        'description' => 'Просмотр страниц',
        'is_system' => false,
    ],
    [
        'name' => 'w_pages',
        'type' => 2,
        'description' => 'Редактирование страниц',
        'is_system' => false,
    ],
    [
        'name' => 'r_metatag',
        'type' => 2,
        'description' => 'Просмотр мета-тегов',
        'is_system' => false,
    ],
    [
        'name' => 'w_metatag',
        'type' => 2,
        'description' => 'Редактирование мета-тегов',
        'is_system' => false,
    ],
    [
        'name' => 'r_localization',
        'type' => 2,
        'description' => 'Просмотр настроек локализации',
        'is_system' => false,
    ],
    [
        'name' => 'w_localization',
        'type' => 2,
        'description' => 'Редактирование настроек локализации',
        'is_system' => false,
    ],
    [
        'name' => 'r_menu',
        'type' => 2,
        'description' => 'Просмотр меню',
        'is_system' => false,
    ],
    [
        'name' => 'w_menu',
        'type' => 2,
        'description' => 'Редактирование меню',
        'is_system' => false,
    ],
    [
        'name' => 'r_dashboard',
        'type' => 2,
        'description' => 'Просмотр рабочего стола',
        'is_system' => false,
    ],
    [
        'name' => 'w_dashboard',
        'type' => 2,
        'description' => 'Редактирование рабочего стола',
        'is_system' => false,
    ],
    [
        'name' => 'wg_worker_log',
        'type' => 2,
        'description' => 'Виджет инфрормации о неотправленных сообщений из очереди',
        'is_system' => false,
    ],
    [
        'name' => 'upload',
        'type' => 2,
        'description' => 'Загрузка файлов на сервер',
        'is_system' => false,
    ],
];
