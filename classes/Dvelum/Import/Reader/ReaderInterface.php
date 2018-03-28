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
use \Iterator;

interface ReaderInterface
{
    public function __construct(ConfigInterface $config);

    /**
     * Read multiple records
     * @param int $limit
     * @return array
     */
    public function readRecords(int $limit) : array;

    /**
     * Set data source for reading
     * @param mixed $dataSource
     */
    public function setDataSource($dataSource): void;
    /**
     * Get record iterator
     * @return Iterator
     */
    public function getIterator(): Iterator;
}