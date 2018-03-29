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
use Dvelum\Filter;
use Dvelum\Import\Settings;
use Dvelum\Request;
use Dvelum\Response;

class Controller extends Backend\Controller
{
    /**
     * @var Settings $settings
     */
    protected $settings;

    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        $importConfig = Config::storage()->get('import.php');
        $this->settings = new Settings($importConfig);
    }

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
        $this->response->success($this->settings->settingsList($this->user->getId(), $section));
    }

    /**
     * Load user settings by id
     */
    public function settingsLoadAction()
    {
        $settingId = $this->request->post('id', Filter::FILTER_INTEGER, false);

        if( empty($settingId)){
            $this->response->error($this->lang->get('WRONG_REQUEST'));
            return;
        }
        $settingsData = $this->settings->getUserSettings($this->user->getId(), $settingId);

        if(empty($settingsData)){
            $this->response->error($this->lang->get('WRONG_REQUEST'));
            return;
        }

        $this->response->success($settingsData);
    }

    public function settingsSaveAction()
    {
        if(!$this->checkCanEdit()){
            return;
        }

        $section = $this->request->post('section', Filter::FILTER_STRING, '');
        $settingId =  $this->request->post('settings_id', Filter::FILTER_INTEGER, 0);
        $settingTitle =  $this->request->post('settings_name', Filter::FILTER_STRING, '');

        $columns = $this->request->post('columns', Filter::FILTER_ARRAY, false);
        $firstRow = $this->request->post('first_row', Filter::FILTER_INTEGER, false);

        if(empty($settingTitle) || empty($section)){
            $this->response->error($this->lang->get('FILL_FORM'));
            return;
        }

        if($settingId){
            $settingsData = $this->settings->getUserSettings($this->user->getId(), $settingId);
            if(empty($settingsData)){
                $this->response->error($this->lang->get('WRONG_REQUEST'));
                return;
            }
        }

        $config = [
            'columns' => $columns,
            'first_row' => $firstRow
        ];

        $result = $this->settings->update($settingId, $settingTitle, $this->user->getId(), $section, $config);

        if(empty($result)){
            $this->response->error($this->lang->get('CANT_EXEC'));
            return;
        }
        $this->response->success($result);
    }

    public function settingsDeleteAction()
    {
        if(!$this->checkCanDelete()){
            return;
        }
        $settingId =  $this->request->post('id', Filter::FILTER_INTEGER, 0);
        if(empty($settingId)){
            $this->response->error($this->lang->get('WRONG_REQUEST'));
            return;
        }

        if($this->settings->delete($settingId, $this->user->getId())){
            $this->response->success();
        }else{
            $this->response->error($this->lang->get('CANT_EXEC'));
        }
    }
}