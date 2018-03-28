<?php
/**
 * DVelum project http://code.google.com/p/dvelum/ , https://github.com/k-samuel/dvelum , http://dvelum.net
 * Copyright (C) 2011-2018  Kirill Yegorov
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Dvelum\App\Backend\Import\Storage;

use Dvelum\App\Backend;
use Dvelum\Config;
use Dvelum\Orm\Model;

class Controller extends Backend\Controller
{
    public function getModule(): string
    {
        return 'Dvelum_Import_Storage';
    }

    public function getObjectName(): string
    {
        return 'dvelum_import';
    }

    /**
     * Get user settings
     */
    public function settingsListAction()
    {
        $section = $this->request->post('section', 'string', '');

        $importConfig = Config::storage()->get('import.php');
        /**
         * @var \Model\Dvelum\Import $settingsModel
         */
        $settingsModel = Model::factory($importConfig->get('settings_object'));
        $this->response->success($settingsModel->settingsList($this->user->getId(), $section));
    }
}