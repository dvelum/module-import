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

use Dvelum\Import\Writer\WriterInterface;
use Dvelum\Config\ConfigInterface;
use Dvelum\Import\Reader\ReaderInterface;
use Dvelum\Import\Writer\Result;
use Dvelum\Orm\Record;

abstract class AbstractWriter implements WriterInterface
{
    const MODE_INSERT = 'insert';
    const MODE_INSERT_UPDATE = 'insert_update';

    /**
     * @var ConfigInterface $config
     */
    protected $config;
    /**
     * @var ReaderInterface $reader
     */
    protected $reader;
    /**
     * @var array $errors
     */
    protected $errors = [];

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param ReaderInterface $reader
     */
    public function setReader(ReaderInterface $reader): void
    {
        $this->reader = $reader;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $settings
     * @return Result
     */
    abstract public function import(array $settings): Result;
}