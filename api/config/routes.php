<?php
return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['auth'],
        'pluralize' => false,
        'extraPatterns' => [
            'POST,OPTIONS signin' => 'signin',
            'POST,OPTIONS logout' => 'logout',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['user'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,OPTIONS' => 'info',
            'PUT,OPTIONS change-password' => 'change-password',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['references'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,OPTIONS standard' => 'standard-list',
            'GET,OPTIONS hardness' => 'hardness-list',
            'GET,OPTIONS outer-diameter' => 'outer-diameter-list',
            'GET,OPTIONS customer' => 'customer-list',
            'GET,OPTIONS control-method' => 'control-method-list',
            'GET,OPTIONS control-result' => 'control-result-list',
            'GET,OPTIONS wall-thickness' => 'wall-thickness-list',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['certificate'],
        'pluralize' => false,
        'extraPatterns' => [
            'GET,OPTIONS published' => 'list-published',
            'GET,OPTIONS draft' => 'list-draft',
            'GET,OPTIONS deleted' => 'list-deleted',
            'GET,OPTIONS approve' => 'list-approve',

            'POST,OPTIONS ' => 'create',
            'GET,OPTIONS <id>' => 'common-step',
            'PUT,OPTIONS <id>' => 'update-common-step',
            'DELETE,OPTIONS <id>' => 'delete',
            'PUT,OPTIONS <id>/restore' => 'restore',
            'POST,OPTIONS <id>/copy' => 'copy',
            'PUT,OPTIONS <id>/refund' => 'refund',
            'GET,OPTIONS <id>/download' => 'download',
            'GET,OPTIONS <id>/all-fields' => 'all-fields',
            'PUT,OPTIONS <id>/approve' => 'approve',

            'GET,OPTIONS <id>/non-destructive-test' => 'non-destructive-test-step',
            'PUT,OPTIONS <id>/non-destructive-test' => 'update-non-destructive-test-step',

            'GET,OPTIONS <idCertificate>/wall-thickness-info/<idWallThickness>' => 'wall-thickness-info',
            'GET,OPTIONS <id>/detail-tube' => 'detail-tube-step',
            'PUT,OPTIONS <id>/detail-tube' => 'update-detail-tube-step',

            'GET,OPTIONS <id>/rolls-sort' => 'rolls-sort-step',
            'PUT,OPTIONS <id>/rolls-sort' => 'update-rolls-sort-step',

            'GET,OPTIONS <id>/cylinder' => 'cylinder-step',
            'PUT,OPTIONS <id>/cylinder' => 'cylinder-update-step',

            'GET,OPTIONS <id>/note' => 'note-step',
            'PUT,OPTIONS <id>/note' => 'update-note-step',

            'GET,OPTIONS <id>/signature' => 'signature-step',
            'PUT,OPTIONS <id>/signature' => 'update-signature-step',

            'POST,OPTIONS <idCertificate>/meld' => 'create-meld',
            'DELETE,OPTIONS <idCertificate>/meld/<idMeld>' => 'delete-meld',

            'POST,OPTIONS <idCertificate>/<idMeld>/roll' => 'create-roll',
            'DELETE,OPTIONS <idCertificate>/<idMeld>/roll/<idRoll>' => 'delete-roll',

            'POST,OPTIONS <idCertificate>/note' => 'create-note',
            'DELETE,OPTIONS <idCertificate>/note/<idNote>' => 'delete-note',

            'POST,OPTIONS <idCertificate>/signature' => 'create-signature',
            'DELETE,OPTIONS <idCertificate>/signature/<idSignature>' => 'delete-signature',
        ],
    ],
];
