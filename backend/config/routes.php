<?php

return [
    'site/<action>' => 'site/<action>',

    '/' => 'site/index',

    'settings/<action>' => 'settings/default/<action>',
    'settings' => 'settings/default/index',

    'permit/<action>' => 'permit/access/<action>',
    'permit' => 'permit/access/index',

    'gridview/export/download' => 'gridview/export/download',

    'dynagrid/settings/<action>' => 'dynagrid/settings/<action>',

    'references/<controller>/<action>' => 'references/<controller>/<action>',
    'references/<controller>' => 'references/<controller>/index',
];