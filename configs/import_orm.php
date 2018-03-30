<?php
return [
    /*
     * Import mode
     * insert - Insert ignore existing records
     * insert_update - Insert new and update existing records
     */
    'mode' => 'insert_update',
    /*
     * Import adapter
     * \Dvelum\Import\Adapter\OrmRecord - use Orm\Record  (triggers,log,validation, low performance)
     * \Dvelum\Import\Adapter\OrmModel - use Orm\Model bulk operations (high performance, no ORM triggers, no Validation)
     */
    'adapter' => '\\Dvelum\\Import\\Adapter\\OrmRecord',
    'bucket_size' => 200,
];