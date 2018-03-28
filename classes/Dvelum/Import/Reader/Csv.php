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

namespace Dvelum\Import\Reader;

use Dvelum\Config\ConfigInterface;
use \SplFileObject;

class Csv implements ReaderInterface
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;
    /**
     * @var SplFileObject $dataSource
     */
    protected $dataSource;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Read multiple records
     * @param int $limit
     * @return array
     */
    public function readRecords(int $limit): array
    {
        $data = [];
        $index = 0;
        foreach ($this->dataSource as $record){
            $data[] = $record;
            $index++;
            if($limit && $index == $limit){
                break;
            }
        }
        return $data;
    }

    /**
     * Get record iterator
     * @return \Iterator
     */
    public function getIterator(): \Iterator
    {
        return $this->dataSource;
    }

    /**
     * Set data source for reading
     * @param mixed $dataSourcePath
     */
    public function setDataSource($dataSourcePath): void
    {
        $this->dataSource = new SplFileObject($dataSourcePath);
        $this->dataSource->setCsvControl($this->config->get('delimiter'), $this->config->get('enclosure'), $this->config->get('escape'));
        $this->dataSource->setFlags(SplFileObject::READ_CSV);
    }

}