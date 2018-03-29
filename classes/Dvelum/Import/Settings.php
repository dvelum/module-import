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

use Dvelum\Config\ConfigInterface;
use Dvelum\Orm\Model;
use Dvelum\Orm\Record;
use \Exception;

class Settings
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;

    /**
     * @var Model $model
     */
    protected $model;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->model = Model::factory($config->get('settings_object'));
    }

    /**
     * Get default settings, create if not exist
     * @param int $userId
     * @param string $section
     * @return array
     */
    public function getDefaultSettings(int $userId, string $section) : array
    {
        $data = $this->model->query()->filters([
            'user' => $userId,
            'section' => $section,
            'name' => 'default'
        ])->fetchRow();

        if(!empty($data)){
            $data['settings'] = json_decode($data['settings'] , true);
            return $data;
        }

        try{
            $object = Record::factory($this->model->getObjectName());
            $object->setValues([
                'user'=>$userId,
                'section'=>$section,
                'default' => true,
                'settings'=>json_encode([]),
                'name'=> 'Default',
                'update_date' => date('Y-m-d H:i:s')
            ]);

            if(!$object->save())
                throw new Exception('Cannot save object');

            $data = $object->getData();
            $data['settings'] = [];
            return $data;
        }catch (Exception $e){
            $this->model->logError('getDefaultSettings error [user:' . $userId . ', section:' . $section . ']:' . $e->getMessage());
            return [];
        }
    }
    /**
     * Find user settings
     * @param int $userId
     * @param string $section
     * @param string $name
     * @return array
     */
    public function getSettings(int $userId, string $section, string $name = 'default') : array
    {
        $data = $this->model->query()->filters([
            'user' => $userId,
            'section' => $section,
            'name' => $name
        ])->fetchRow();

        if(!empty($data)) {
            return json_decode($data['settings'] , true);
        } else {
            return [];
        }
    }

    /**
     * Find all user settings for section
     * @param int $userId
     * @param string $section
     * @return array
     */
    public function settingsList(int $userId , string $section , array $fields = ['id','name','section']) : array
    {
        $data =  $this->model->query()->filters([
            'user' => $userId,
            'section' => $section
        ])->fetchAll();

        return $data;
    }

    /**
     * Get settings by id and user id
     * @param int $userId
     * @param int $settingsId
     * @return array
     */
    public function getUserSettings(int $userId, int $settingsId) : array
    {
        $data =  $this->model->query()->filters([
            'user' => $userId,
            'id' => $settingsId
        ])->fetchRow();

        if(!empty($data)) {
            $data['settings'] = json_decode($data['settings']);
            return $data;
        } else {
            return [];
        }
    }

    /**
     * Update settings record, create if not exist
     * @param int $settingId
     * @param string $settingName
     * @param int $user
     * @param string $section
     * @param array $settings
     * @return array|null
     */
    public function update(int $settingId, string $settingName, int $user, string $section, array $settings) : ?array
    {
        if($settingId){
            try{
                $object = Record::factory($this->model->getObjectName(), $settingId);
            }catch (Exception $e){
                $this->model->logError($e->getMessage());
                return null;
            }

            if($object->get('user')!=$user){
                return null;
            }
        }else{
            $object = Record::factory($this->model->getObjectName());
        }

        /**
         * @var Record $object
         */

        try{
            $object->setValues([
               'name' => $settingName,
               'section' => $section,
               'settings' => json_encode($settings),
               'user' => $user,
               'update_date' => date('Y-m-d H:i:s')
            ]);
            if(!$object->save()){
                throw new Exception('Cannot save settings object');
            }

        }catch (Exception $e){
            $this->model->logError('setSettings error [user:'.$user.', section:'.$section.']:' . $e->getMessage());
            return null;
        }

        return $object->getData();
    }

    /**
     * Delete user settings
     * @param int $settingId
     * @param int $user
     * @return bool
     */
    public function delete(int $settingId, int $user) : bool
    {
        /**
         * @var Record $object
         */
        try{
            $object = Record::factory($this->model->getObjectName(), $settingId);
        }catch (Exception $e){
            $this->model->logError($e->getMessage());
            return false;
        }

        if($object->get('user')!=$user || $object->get('default')){
            return false;
        }

        return $object->delete();
    }
}