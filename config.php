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
    'require'=>[
      'dvelum-module-filestorage'
    ],
    'autoloader'=> [
        './classes'
    ],
    'objects' =>[
        'dvelum_import',
        'dvelum_import_tmp'
    ],
    'post-install'=>'\\Dvelum\\Import\\Installer'
];