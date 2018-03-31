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

namespace Dvelum\Import;

use Dvelum\Config;
use Dvelum\Config\ConfigInterface;
use Dvelum\Import\Reader\ReaderInterface;

class Reader
{
    static public function factory(ConfigInterface $config) : ReaderInterface
    {
        $readerClass = $config->get('adapter');
        $readerConfig = Config\Factory::create($config->get('config'));
        $reader = new $readerClass($readerConfig);
        return $reader;
    }
}