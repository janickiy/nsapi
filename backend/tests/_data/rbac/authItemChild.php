<?php

return [
    [
        'parent' => 'admin',
        'child' => 'w_user',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_user',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_user',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_user',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_user',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_site',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_site',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_site',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_site',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_site',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_site',
    ],
    [
        'parent' => 'admin',
        'child' => 'backend',
    ],
    [
        'parent' => 'moderator',
        'child' => 'backend',
    ],
    [
        'parent' => 'developer',
        'child' => 'backend',
    ],
    [
        'parent' => 'admin',
        'child' => 'wg_user_info',
    ],
    [
        'parent' => 'moderator',
        'child' => 'wg_user_info',
    ],
    [
        'parent' => 'developer',
        'child' => 'wg_user_info',
    ],
    [
        'parent' => 'admin',
        'child' => 'wg_user_admin_info',
    ],
    [
        'parent' => 'moderator',
        'child' => 'wg_user_admin_info',
    ],
    [
        'parent' => 'developer',
        'child' => 'wg_user_admin_info',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_settings',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_settings',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_settings',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_settings',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_settings',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_settings',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_mailnotify',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_mailnotify',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_mailnotify',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_mailnotify',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_mailnotify',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_mailnotify',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_pages',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_pages',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_pages',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_pages',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_pages',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_pages',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_metatag',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_metatag',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_metatag',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_metatag',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_metatag',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_metatag',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_localization',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_localization',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_localization',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_localization',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_localization',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_localization',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_menu',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_menu',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_menu',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_menu',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_menu',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_menu',
    ],
    [
        'parent' => 'admin',
        'child' => 'r_dashboard',
    ],
    [
        'parent' => 'moderator',
        'child' => 'r_dashboard',
    ],
    [
        'parent' => 'developer',
        'child' => 'r_dashboard',
    ],
    [
        'parent' => 'admin',
        'child' => 'w_dashboard',
    ],
    [
        'parent' => 'moderator',
        'child' => 'w_dashboard',
    ],
    [
        'parent' => 'developer',
        'child' => 'w_dashboard',
    ],
    [
        'parent' => 'admin',
        'child' => 'wg_worker_log',
    ],
    [
        'parent' => 'moderator',
        'child' => 'wg_worker_log',
    ],
    [
        'parent' => 'admin',
        'child' => 'upload',
    ],

    // для тестирования удаления роли
    [
        'parent' => 'test_delete_role',
        'child' => 'r_user',
    ],
    [
        'parent' => 'test_delete_role',
        'child' => 'w_user',
    ],
];
