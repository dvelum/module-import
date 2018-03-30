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

use Dvelum\Config\ConfigInterface;
use Dvelum\Import\Adapter\AdapterInterface;
use Dvelum\Import\Reader\ReaderInterface;
use Dvelum\Orm\Record;

abstract class AbstractAdapter implements AdapterInterface
{
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

    public function setReader(ReaderInterface $reader): void
    {
        $this->reader = $reader;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    abstract public function import(): bool;

    protected function getConfig() : Record\Config
    {
        return Record\Config::factory($this->config->get('object'));
    }
}