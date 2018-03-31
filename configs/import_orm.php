<?php
return [
    /*
     * Import mode
     * insert - Insert ignore existing records
     * insert_update - Insert new and update existing records
     */
    'mode' => 'insert',
    /*
     * Import adapter
     * \Dvelum\Import\Writer\Orm\OrmRecord - use Orm\Record  (triggers,log,validation, low performance)
     * \Dvelum\Import\Writer\Orm\OrmModel - use Orm\Model bulk operations (high performance, no ORM triggers, no Validation)
     */
    'adapter' => '\\Dvelum\\Import\\Writer\\Orm\\OrmRecord',
    'bucket_size' => 200,
];