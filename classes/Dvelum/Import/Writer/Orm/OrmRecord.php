<?php
/**
 *  Copyright (C) 2018  Kirill Yegorov
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Dvelum\Import\Writer\Orm;

use Dvelum\Import\Writer\Result;
use Dvelum\Import\Writer\WriterInterface;
use \Exception;

use Dvelum\Orm\Record;

class OrmRecord extends AbstractWriter implements WriterInterface
{
    public function import(array $settings): Result
    {
        if(empty($this->reader)){
            throw new Exception('Reader is undefined');
        }

       // $result = new Result();
        $errors = 0;
        $success = 0;
        $new = 0;
        $update = 0;
        $messages = [];
        $time = microtime(true);

        $importMap = [
            'fields' => [],
            'unique_columns' => []
        ];
        $objectName = $settings['object'];

        $config = Record\Config::factory($objectName);
        $fields = $config->getFields();
        $indexes = $config->getIndexesConfig(false);

        foreach ($indexes as $index)
        {
            if($index['unique']){
                foreach ($index['columns'] as $fieldName){
                    $field = $config->getField($fieldName);
                    if(!$field->isRequired() || $field->isNull()){
                        continue 2;
                    }
                }
                $importMap['fields']['unique_columns'][] = $index['columns'];
            }
        }

        foreach ($fields as $field){
            $importMap['fields'][] = $field->getName();
        }


        $operationType = $this->config->get('mode');

        foreach ($this->reader as $item)
        {
            switch ($operationType){
                case self::MODE_INSERT :


                    $data = [];
                    foreach ($importMap['fields'] as $fieldName){
                        if(isset($config['columns'][$fieldName])){
                            $index = intval($config['columns'][$fieldName]);
                            if($index > -1){
                                if(isset($item[$index])){
                                    $data[$fieldName] = $item[$index];
                                    if($data[$fieldName] === 'NULL' && $config->getField($fieldName)->isNull()){
                                        $data[$fieldName] = null;
                                    }
                                }
                            }
                        }
                    }
                    if(empty($data)){
                        continue;
                    }
                    try{
                        $object = Record::factory($objectName);
                        $object->setValues($data);
                        if(!$object->save()){
                            throw new Exception('Cannot save object');
                        }
                        $success++;
                        $new++;
                    }catch (Exception $e){
                        if(count($messages) < 10){
                            $messages[] = $e->getMessage();
                        }
                        $errors++;
                    }

                    break;

                case  self::MODE_INSERT_UPDATE:

            }
        }

        $time = microtime(true) - $time;

        $result = new Result();

        $result->setErrors($errors);
        $result->setMessages($messages);
        $result->setSuccess($success);
        $result->setNew($new);
        $result->setUpdate($update);
        $result->setTime($time);

        return $result;
    }
}