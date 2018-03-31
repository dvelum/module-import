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
use Dvelum\Orm\Model;

class OrmModel extends AbstractWriter
{
    public function import(): Result
    {
        $objectName = $settings['object'];
        try{
            $objectConfig = Record\Config::factory($this->config->get('object'));
        }catch (\Exception $e){
            $this->errors[] = $e->getMessage();
            return false;
        }

        foreach ($this->reader as $record)
        {

        }
    }
}