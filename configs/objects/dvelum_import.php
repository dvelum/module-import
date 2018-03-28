<?php return array (
    'table' => 'dvelum_import',
    'engine' => 'InnoDB',
    'connection' => 'default',
    'acl' => false,
    'parent_object' => '',
    'rev_control' => false,
    'save_history' => false,
    'link_title' => '',
    'disable_keys' => false,
    'readonly' => false,
    'locked' => false,
    'primary_key' => 'id',
    'use_db_prefix' => true,
    'slave_connection' => 'default',
    'log_detalization' => 'default',
    'fields' =>
        array (
            'section' =>
                array (
                    'type' => '',
                    'unique' => '',
                    'db_isNull' => false,
                    'required' => true,
                    'validator' => '',
                    'db_type' => 'varchar',
                    'db_default' => false,
                    'db_len' => 255,
                    'is_search' => false,
                    'allow_html' => false,
                ),
            'user' =>
                array (
                    'type' => 'link',
                    'unique' => '',
                    'db_isNull' => false,
                    'required' => true,
                    'validator' => '',
                    'link_config' =>
                        array (
                            'link_type' => 'object',
                            'object' => 'user',
                        ),
                    'db_type' => 'bigint',
                    'db_default' => false,
                    'db_unsigned' => true,
                ),
            'settings' =>
                array (
                    'type' => '',
                    'unique' => '',
                    'db_isNull' => true,
                    'required' => false,
                    'validator' => '',
                    'db_type' => 'longtext',
                    'db_default' => false,
                    'is_search' => false,
                    'allow_html' => true,
                ),
            'name' =>
                array (
                    'type' => '',
                    'unique' => '',
                    'db_isNull' => false,
                    'required' => true,
                    'validator' => '',
                    'db_type' => 'varchar',
                    'db_default' => false,
                    'db_len' => 255,
                    'is_search' => false,
                    'allow_html' => false,
                ),
            'update_date' =>
                array (
                    'type' => '',
                    'unique' => '',
                    'db_isNull' => false,
                    'required' => true,
                    'validator' => '',
                    'db_type' => 'datetime',
                    'db_default' => false,
                ),
        ),
    'indexes' =>
        array (
            'section_user' =>
                array (
                    'columns' =>
                        array (
                            0 => 'section',
                            1 => 'user',
                        ),
                    'unique' => false,
                    'fulltext' => false,
                    'PRIMARY' => false,
                ),
        ),
); 