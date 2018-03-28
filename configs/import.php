<?php
return [
    'format' => [
        '.csv' => [
            'adapter' => '\Dvelum\\Import\\Reader\\Csv',
            'config' => [
                'delimiter' => ';',
                'enclosure' => '"',
                'escape' => '\\'
            ]
        ]
    ],
    // localization dictionary
    'lang'=>'dvelum_import',
    // file upload field name
    'form_field' => 'file',
    // Error log object
    'log_object' => 'error_log',
    // limit records for preview, 0 - disable limit
    'limit_preview' => 100,
    // Settings ORM Object
    'settings_object' => 'dvelum_import'
];