<?php
return [
    'id' => 'dvelum-module-import',
    'version' => '2.0.0',
    'author' => 'Kirill Yegorov',
    'name' => 'DVelum Import',
    'configs' => './configs',
    'locales' => './locales',
    'resources' =>'./resources',
    'vendor'=>'Dvelum',
    'autoloader'=> [
        './classes'
    ],
    'objects' =>[
        ''
    ],
    'post-install'=>'\\Dvelum\\Import\\Installer'
];