<?php
declare(strict_types=1);

namespace Dvelum\App\Console\Import;

use Dvelum\App\Console;
use Dvelum\Orm\Model;
use Dvelum\Config;
use Dvelum\Db\Select\Filter;
use Dvelum\FileStorage;

class Clear extends Console\Action
{
    /**
     * @var FileStorage\AbstractAdapter $filestorage
     */
    protected $fileStorage;
    /**
     * @var Model $tmpModel
     */
    protected $tmpModel;

    public function action(): bool
    {
        $importConfig = Config::storage()->get('import.php');
        $this->tmpModel = Model::factory($importConfig->get('tmp_files_object'));
        /**
         * @var \Model_Filestorage
         */
        $this->fileStorage = Model::factory('filestorage')->getStorage();
        $date = new \DateTime();
        $date->sub(new \DateInterval($importConfig->get('clear_interval')));

        $this->stat['deleted'] = 0;
        $limitParam = $importConfig->get('clear_limit');
        for($i=0,$limit=$importConfig->get('clear_iterations');$i<$limit;$i++)
        {
            $list = $this->tmpModel->query()
                                    ->filters([
                                        new Filter('upload_date',$date->format('Y-m-d H:i:s'), Filter::LT)
                                     ])
                                    ->params(['limit'=> $limitParam])
                                    ->fetchAll();
            if(empty($list)){
                return true;
            }

            foreach ($list as $item){
                if(!$this->deleteRecord($item)){
                    return false;
                }
                $this->stat['deleted'] ++;
            }
        }
    }

    protected function deleteRecord(array $item) : bool
    {
        $db = $this->tmpModel->getDbConnection();
        $db->beginTransaction();
        if(!$this->tmpModel->remove($item['id']) || !$this->fileStorage->remove($item['file'])){
            $db->rollback();
            return false;
        }
        $db->commit();
        return true;
    }
}