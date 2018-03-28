<?php
/**
 * DVelum project http://code.google.com/p/dvelum/ , https://github.com/k-samuel/dvelum , http://dvelum.net
 * Copyright (C) 2011-2017  Kirill Yegorov
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
namespace Dvelum\App\Backend\Import;

use Dvelum\App\Backend;
use Dvelum\Config;
use Dvelum\Import\Manager;
use Dvelum\Lang;
use Dvelum\Orm;
use Dvelum\Request;
use Dvelum\Response;


class Controller extends Backend\Controller
{
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        Lang::addDictionaryLoader('dvelum_import', $this->appConfig->get('language').'/dvelum_import.php');
    }

    public function getModule(): string
    {
        return 'Dvelum_Import';
    }

    public function getObjectName(): string
    {
        return 'dvelum_import';
    }


    /**
     * Get list of import acceptable objects
     */
    public function listObjectsAction()
    {
        $data = [];
        $dbObjectManager = new Orm\Record\Manager();
        foreach($dbObjectManager->getRegisteredObjects() as $object)
        {
            $cobjectConfig =  Orm\Record\Config::factory($object);
            $data[] = ['id' => $object, 'title'=> $cobjectConfig->getTitle()];
        }
        $this->response->success($data);
    }

    public function uploadAction()
    {
        if(!$this->checkCanEdit()){
            return;
        }

        $objectName = strtolower($this->request->post('object', 'string', ''));
        $section = $this->request->post('section', 'string', '');

        if(empty($objectName) || !Orm\Record\Config::configExists($objectName) || empty($section)){
            $this->response->error($this->lang->get('WRONG_REQUEST'));
            return;
        }

        /**
         * @var \Model_Filestorage $model
         */
        $model = Orm\Model::factory('Filestorage');

        $importConfig = Config::storage()->get('import.php');
        $manager = new Manager($importConfig);
        $manager->setStorage($model->getStorage());

        if(!$manager->upload($this->request, $this->user)){
            $this->response->error(implode(',', $manager->getErrors()));
            return;
        }

        $uploadId = $manager->getUploadId();
        $data = $manager->getPreview();
        /**
         * @var \Model\Dvelum\Import $settingsModel
         */
        $settingsModel = Orm\Model::factory($importConfig->get('settings_object'));

        $userSettings = $settingsModel->getSettings($this->user->getId(), $section);
        if(empty($userSettings)){
            $userSettings = false;
        }

        $expectedColumns = $this->getExpectedColumns($objectName);

        $this->response->json([
           'success' => true,
           'data' => $data,
           'settings' =>  $userSettings,
           'col_count' => count($data[0]),
           'expectedColumns' => $expectedColumns
        ]);

    }

    /**
     * Generate expected columns for orm record
     * @param string $objectName
     * @return array
     */
    protected function getExpectedColumns(string $objectName) : array
    {
        $expectedColumns = [];
        $config = Orm\Record\Config::factory($objectName);
        $fields = $config->getFields();
        foreach ($fields as $field)
        {
            $expectedColumns[] = [
                'id'=> $field->getName(),
                'text'=> $field->getTitle(),
                'columnIndex' => -1,
                'required' => $field->isRequired()
            ];
        }
        return  $expectedColumns;
    }
}