<?php
namespace Dvelum\Import;

use Dvelum\Config\ConfigInterface;
use Dvelum\App\Session\User;
use Dvelum\Orm\Model;
use Dvelum\Externals;

class Installer extends Externals\Installer
{
    /**
     * Install
     * @param ConfigInterface $applicationConfig
     * @param ConfigInterface $moduleConfig
     * @return bool
     */
    public function install(ConfigInterface $applicationConfig, ConfigInterface $moduleConfig) : bool
    {
        // Add permissions
        $userInfo = User::getInstance()->getInfo();
        /**
         * @var \Model_Permissions $permissionsModel
         */
        $permissionsModel = Model::factory('Permissions');
        if (!$permissionsModel->setGroupPermissions($userInfo['group_id'], 'Dvelum_Import', 1, 1, 1, 1)) {
            return false;
        }
        if (!$permissionsModel->setGroupPermissions($userInfo['group_id'], 'Dvelum_Import_Storage', 1, 1, 1, 1)) {
            return false;
        }
        return true;
    }

    /**
     * Uninstall
     * @param ConfigInterface $applicationConfig
     * @param ConfigInterface $moduleConfig
     * @return bool
     */
    public function uninstall(ConfigInterface $applicationConfig, ConfigInterface $moduleConfig) : bool
    {

    }
}