<?php return array (
  'table' => 'dvelum_import_tmp',
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
    'file' => 
    array (
      'type' => 'link',
      'unique' => '',
      'db_isNull' => false,
      'required' => true,
      'validator' => '',
      'link_config' => 
      array (
        'link_type' => 'object',
        'object' => 'filestorage',
      ),
      'db_type' => 'bigint',
      'db_default' => false,
      'db_unsigned' => true,
    ),
    'upload_date' => 
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
    'upload_date' => 
    array (
      'columns' => 
      array (
        0 => 'upload_date',
      ),
      'unique' => false,
      'fulltext' => false,
      'PRIMARY' => false,
    ),
  ),
); 