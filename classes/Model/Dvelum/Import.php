<?php
namespace Model\Dvelum;

use Dvelum\Orm\Model;

class Import extends Model
{
    /**
     * Find user settings
     * @param int $userId
     * @param string $section
     * @param string $name
     * @return array
     */
    public function getSettings(int $userId, string $section, string $name = 'default') : array
    {
        $data = $this->query()->filters([
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
    public function settingsList(int $userId , string $section , array $fields = ['id','name','section', 'update_date']) : array
    {
        return $this->query()->filters([
            'user' => $userId,
            'section' => $section
        ])->fetchAll();
    }
}