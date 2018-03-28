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

use Dvelum\App\Session\User;
use Dvelum\Config;
use Dvelum\Config\ConfigInterface;
use Dvelum\FileStorage\AbstractAdapter;
use Dvelum\Import\Reader\ReaderInterface;
use Dvelum\Lang;
use Dvelum\Request;
use Dvelum\File;
use Dvelum\Orm\Model;
use \Exception;

class Manager
{
    /**
     * @var ConfigInterface $config
     */
    protected $config;
    /**
     * @var AbstractAdapter $storage
     */
    protected $storage;
    /**
     * @var array $errors
     */
    protected $errors = [];
    /**
     * @var Lang $lang
     */
    protected $lang;

    protected $uploadId;
    protected $fileExt;
    protected $filePath;
    /**
     * @var ReaderInterface $reader
     */
    protected $reader;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->lang = Lang::lang($config->get('lang'));
    }

    /**
     * Check format reader
     * @param string $ext
     * @return bool
     */
    public function isSupportedFormat(string $ext) : bool
    {
        $ext = strtolower($ext);
        $readers = $this->config->get('format');
        if(isset($readers[$ext])){
            return true;
        }
        return false;
    }

    /**
     * Change files storage adapter
     * @param AbstractAdapter $storage
     */
    public function setStorage(AbstractAdapter $storage) : void
    {
        $this->storage = $storage;
    }

    /**
     * Get error messages
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * Upload user file
     * @param Request $request
     * @param User $user
     * @throws Exception
     * @return bool
     */
    public function upload(Request $request , User $user) : bool
    {
        if(!$this->storage instanceof AbstractAdapter){
            throw new Exception('Import\Manager :: Undefined filestorage');
        }

        $this->uploadId = '';
        $this->fileExt = '';
        $this->reader = null;
        $this->filePath = '';

        $files = $request->files();

        $formField = $this->config->get('form_field');

        if (!isset($files[$formField]) || empty($files[$formField])){
            $this->errors[] = $this->lang->get('no_file');
            return false;
        }

        $file = $files['file'];
        $ext = File::getExt($file['name']);

        if(!$this->isSupportedFormat($ext)){
            $this->errors[] = $this->lang->get('unsupported_format');
            return false;
        }

        $this->storage->getConfig()->set('user_id', $user->getId());
        if($this->config->offsetExists('log_object') && !empty($this->config->get('log_object'))){
            $this->storage->setLog(Model::factory($this->config->get('log_object'))->getLogsAdapter());
        }

        $files = $this->storage->upload();
        if (empty($files)) {
            $this->errors[] = $this->lang->get('no_file');
            return false;
        }

        $uploadedFile = $files[0];

        $this->uploadId = $uploadedFile['id'];
        $this->fileExt = $ext;
        $this->filePath = $this->storage->getPath() . '/' . $uploadedFile['path'];

        return true;
    }

    /**
     * Get last uploaded file identifier
     * @return string
     */
    public function getUploadId() : string
    {
        return (string) $this->uploadId;
    }

    /**
     * Get records for import data preview
     * @return array
     */
    public function getPreview() : array
    {
        $limit = $this->config->get('limit_preview');
        $reader = $this->getReader();
        return $reader->readRecords($limit);
    }

    /**
     * Get import reader
     * @return ReaderInterface
     */
    public function getReader() : ReaderInterface
    {
        if(empty($this->reader)){
            $readerConfig = $this->config->get('format')[$this->fileExt];
            $readerClass = $readerConfig['adapter'];
            $readerConfig = Config\Factory::create($readerConfig['config']);
            $this->reader = new $readerClass($readerConfig);
            $this->reader->setDataSource($this->filePath);
        }
        return $this->reader;
    }
}