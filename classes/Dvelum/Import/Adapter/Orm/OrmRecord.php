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

namespace Dvelum\Import\Adapter\Orm;

use Dvelum\Orm\Record;

class OrmRecord extends AbstractAdapter
{
    public function import(): bool
    {
        $importMap = [
            'fields' => [],
            'unique_columns' => []
        ];

        $config = $this->getConfig();
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
    }
}